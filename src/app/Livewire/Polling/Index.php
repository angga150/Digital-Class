<?php

namespace App\Livewire\Polling;

use App\Models\Poll;
use App\Support\CurrentClass;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.member')]
#[Title('Suara Kelas — Digital Class')]
class Index extends Component
{
    use WithPagination;

    public string $filter = 'open';

    public string $question = '';

    public string $description = '';

    public array $options = ['', ''];

    public string $ends = '';

    public string $visibility = 'after_vote';

    #[Locked]
    public bool $formOpen = false;

    #[Locked]
    public string $notice = '';

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('create', Poll::class);
        $this->closeForm();
        $this->formOpen = true;
    }

    public function closeForm(): void
    {
        $this->reset('question', 'description', 'options', 'ends', 'visibility', 'formOpen');
        $this->resetValidation();
    }

    public function addOption(): void
    {
        Gate::authorize('create', Poll::class);
        if (count($this->options) < 6) {
            $this->options[] = '';
        }
    }

    public function removeOption(int $index): void
    {
        Gate::authorize('create', Poll::class);
        if (count($this->options) > 2 && array_key_exists($index, $this->options)) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    public function save(): void
    {
        Gate::authorize('create', Poll::class);
        $this->question = trim($this->question);
        $this->options = array_values(array_map(fn ($value) => is_string($value) ? trim($value) : $value, $this->options));
        $data = $this->validate([
            'question' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'options' => 'required|array|min:2|max:6',
            'options.*' => 'required|string|max:150|distinct:ignore_case',
            'ends' => ['required', 'date_format:Y-m-d\TH:i'],
            'visibility' => ['required', Rule::in(['all', 'after_vote', 'closed', 'admin_only'])],
        ], [
            'required' => ':attribute perlu diisi dulu, ya.',
            'options.*.distinct' => 'Pilihan jawaban tidak boleh sama.',
            'ends.date_format' => 'Pilih tanggal dan jam yang valid.',
        ], ['question' => 'Pertanyaan', 'description' => 'Deskripsi', 'options.*' => 'Pilihan jawaban', 'ends' => 'Batas waktu', 'visibility' => 'Tampilan hasil']);
        $end = CarbonImmutable::createFromFormat('!Y-m-d\TH:i', $data['ends'], 'Asia/Jakarta')->utc();
        if ($end->lessThanOrEqualTo(now()) || $end->greaterThan(CarbonImmutable::parse('2038-01-19 03:14:07', 'UTC'))) {
            $this->addError('ends', 'Batas waktu harus di masa depan, paling akhir 19 Januari 2038 pukul 10:14 WIB.');

            return;
        }
        DB::transaction(function () use ($data, $end) {
            $poll = Poll::create([
                'class_id' => CurrentClass::membership()->class_id, 'created_by' => auth()->id(),
                'question' => $data['question'], 'description' => trim($data['description'] ?? '') ?: null,
                'ends_at' => $end, 'is_active' => true, 'allow_multiple' => false,
                'is_anonymous' => true, 'result_visibility' => $data['visibility'],
            ]);
            foreach ($data['options'] as $order => $option) {
                $poll->options()->create(['option_text' => $option, 'order' => $order]);
            }
        });
        $this->closeForm();
        $this->filter = 'open';
        $this->resetPage();
        $this->notice = 'Polling terbit. Saatnya dengar suara teman sekelas!';
    }

    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);
        $query = Poll::query();
        if ($this->filter === 'open') {
            $query->where('is_active', true)->where('allow_multiple', false)->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()));
        } elseif ($this->filter === 'closed') {
            $query->where(fn ($q) => $q->where('is_active', false)->orWhere('ends_at', '<=', now())->orWhere('allow_multiple', true));
        }

        return view('livewire.polling.index', ['polls' => $query->latest('id')->paginate(6)]);
    }
}
