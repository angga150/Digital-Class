<?php

namespace App\Livewire\Agenda;

use App\Models\Event;
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
#[Title('Agenda Kelas — Digital Class')]
class Index extends Component
{
    use WithPagination;

    public string $filter = 'upcoming';

    #[Locked]
    public bool $formOpen = false;

    #[Locked]
    public ?int $editing = null;

    #[Locked]
    public string $notice = '';

    public string $title = '';

    public string $description = '';

    public string $type = 'academic';

    public string $starts = '';

    public string $ends = '';

    public string $location = '';

    public string $link = '';

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('create', Event::class);
        $this->closeForm();
        $this->formOpen = true;
    }

    public function edit(int $id): void
    {
        $event = Event::findOrFail($id);
        Gate::authorize('update', $event);
        $this->resetValidation();
        $this->editing = $id;
        $this->formOpen = true;
        foreach (['title', 'description', 'type', 'location', 'link'] as $field) {
            $this->$field = $event->$field ?? '';
        }
        $this->starts = $event->event_date->timezone('Asia/Jakarta')->format('Y-m-d\TH:i');
        $this->ends = $event->ends_at?->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') ?? '';
    }

    public function closeForm(): void
    {
        $this->reset('formOpen', 'editing', 'title', 'description', 'type', 'starts', 'ends', 'location', 'link');
        $this->resetValidation();
    }

    public function save(): void
    {
        Gate::authorize('create', Event::class);
        $this->title = trim($this->title);
        $this->link = trim($this->link);
        $data = $this->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:3000',
            'type' => ['required', Rule::in(['academic', 'social', 'deadline', 'other'])],
            'starts' => ['required', 'date_format:Y-m-d\TH:i'],
            'ends' => ['required', 'date_format:Y-m-d\TH:i'],
            'location' => 'nullable|string|max:255',
            'link' => 'nullable|url:http,https|max:255',
        ], [
            'required' => ':attribute perlu diisi dulu, ya.',
            'max' => ':attribute maksimal :max karakter.',
            'date_format' => 'Pilih tanggal dan jam yang valid.',
            'url' => 'Tautan harus berupa alamat http atau https yang valid.',
            'in' => 'Pilih jenis kegiatan yang tersedia.',
        ], [
            'title' => 'Judul', 'description' => 'Deskripsi', 'starts' => 'Waktu mulai',
            'ends' => 'Waktu selesai', 'location' => 'Lokasi', 'link' => 'Tautan',
        ]);
        $start = CarbonImmutable::createFromFormat('!Y-m-d\TH:i', $data['starts'], 'Asia/Jakarta')->utc();
        $end = CarbonImmutable::createFromFormat('!Y-m-d\TH:i', $data['ends'], 'Asia/Jakarta')->utc();
        // Existing event columns use MySQL TIMESTAMP. Validate consistently on SQLite too.
        $maximum = CarbonImmutable::parse('2038-01-19 03:14:07', 'UTC');
        foreach (['starts' => $start, 'ends' => $end] as $field => $date) {
            if ($date->greaterThan($maximum)) {
                $this->addError($field, 'Tanggal paling akhir yang didukung adalah 19 Januari 2038, pukul 10:14 WIB.');
            }
        }
        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }
        if ($start->lessThanOrEqualTo(now())) {
            $this->addError('starts', 'Waktu mulai harus di masa depan (WIB).');

            return;
        }
        if ($end->lessThanOrEqualTo($start)) {
            $this->addError('ends', 'Waktu selesai harus setelah waktu mulai.');

            return;
        }
        DB::transaction(function () use ($data, $start, $end) {
            if ($this->editing) {
                $event = Event::lockForUpdate()->findOrFail($this->editing);
                Gate::authorize('update', $event);
            } else {
                $event = new Event(['class_id' => CurrentClass::membership()->class_id, 'created_by' => auth()->id()]);
            }
            $event->fill([
                'title' => $data['title'], 'description' => trim($data['description'] ?? '') ?: null,
                'type' => $data['type'], 'event_date' => $start, 'ends_at' => $end,
                'location' => trim($data['location'] ?? '') ?: null, 'link' => $data['link'] ?: null,
            ])->save();
        });
        $this->closeForm();
        $this->filter = 'upcoming';
        $this->resetPage();
        $this->notice = 'Agenda tersimpan. Teman sekelas sekarang bisa melihat dan mengisi kehadiran.';
    }

    public function cancel(int $id): void
    {
        DB::transaction(function () use ($id) {
            $event = Event::lockForUpdate()->findOrFail($id);
            Gate::authorize('cancel', $event);
            $event->update(['cancelled_at' => now()]);
        });
        $this->closeForm();
        $this->notice = 'Kegiatan dibatalkan. Catatannya tetap tersedia di filter Dibatalkan.';
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $event = Event::lockForUpdate()->findOrFail($id);
            Gate::authorize('delete', $event);
            $event->delete();
        });
        $this->closeForm();
        $this->notice = 'Agenda dihapus dari daftar. Data tetap tersimpan untuk pemulihan administratif.';
    }

    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);
        $events = Event::withCount(['rsvps as going_count' => fn ($query) => $query->where('status', 'going')])
            ->forStatus($this->filter)
            ->orderBy('event_date', in_array($this->filter, ['completed', 'cancelled'], true) ? 'desc' : 'asc')
            ->orderBy('id')->paginate(9);

        return view('livewire.agenda.index', compact('events'));
    }
}
