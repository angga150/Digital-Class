<?php

namespace App\Livewire\Announcements;

use App\Models\Post;
use App\Support\CurrentClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.member')]
#[Title('Papan Kabar — Digital Class')]
class Index extends Component
{
    use WithPagination;

    public string $body = '';

    public bool $pinned = false;

    public string $filter = 'all';

    #[Locked]
    public bool $formOpen = false;

    #[Locked]
    public ?int $editing = null;

    #[Locked]
    public string $notice = '';

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('publishAnnouncement', Post::class);
        $this->closeForm();
        $this->formOpen = true;
    }

    public function closeForm(): void
    {
        $this->reset('body', 'pinned', 'editing', 'formOpen');
        $this->resetValidation();
    }

    public function edit(int $id): void
    {
        $post = Post::where('type', 'announcement')->findOrFail($id);
        Gate::authorize('manageAnnouncement', $post);
        $this->closeForm();
        $this->editing = $post->id;
        $this->body = $post->content;
        $this->pinned = $post->is_pinned;
        $this->formOpen = true;
    }

    public function save(): void
    {
        Gate::authorize('publishAnnouncement', Post::class);
        $this->body = trim($this->body);
        $this->validate(['body' => 'required|string|max:2000', 'pinned' => 'boolean'], [
            'body.required' => 'Tulis kabarnya dulu, ya.',
            'body.max' => 'Pengumuman maksimal 2.000 karakter.',
        ]);
        DB::transaction(function () {
            if ($this->editing) {
                $post = Post::where('type', 'announcement')->lockForUpdate()->findOrFail($this->editing);
                Gate::authorize('manageAnnouncement', $post);
                $post->update(['content' => $this->body, 'is_pinned' => $this->pinned]);
            } else {
                Post::create([
                    'class_id' => CurrentClass::membership()->class_id, 'user_id' => auth()->id(),
                    'type' => 'announcement', 'content' => $this->body, 'is_pinned' => $this->pinned,
                ]);
            }
        });
        $this->closeForm();
        $this->filter = 'all';
        $this->resetPage();
        $this->notice = 'Kabar tersimpan. Teman sekelas sekarang bisa membacanya.';
    }

    public function pin(int $id, bool $pinned): void
    {
        DB::transaction(function () use ($id, $pinned) {
            $post = Post::where('type', 'announcement')->lockForUpdate()->findOrFail($id);
            Gate::authorize('manageAnnouncement', $post);
            $post->update(['is_pinned' => $pinned]);
        });
        $this->notice = $pinned ? 'Pengumuman disematkan ke bagian atas papan kabar.' : 'Sematan dilepas. Pengumuman tetap tersedia.';
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $post = Post::where('type', 'announcement')->lockForUpdate()->findOrFail($id);
            Gate::authorize('manageAnnouncement', $post);
            $post->delete();
        });
        $this->closeForm();
        $this->notice = 'Pengumuman dihapus dari kelas. Datanya tetap tersimpan untuk pemulihan administratif.';
    }

    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);
        $query = Post::where('type', 'announcement');
        if ($this->filter === 'pinned') {
            $query->where('is_pinned', true);
        }

        return view('livewire.announcements.index', [
            'announcements' => $query->orderByDesc('is_pinned')->latest('created_at')->latest('id')->paginate(8),
        ]);
    }
}
