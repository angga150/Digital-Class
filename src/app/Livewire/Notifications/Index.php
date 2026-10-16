<?php

namespace App\Livewire\Notifications;

use App\Models\ClassNotification;
use App\Support\CurrentClass;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.member')]
#[Title('Notifikasi — Digital Class')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $filter = 'all';

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function read(int $id, bool $read): void
    {
        ClassNotification::findOrFail($id)->update(['read_at' => $read ? now() : null]);
    }

    public function readAll(): void
    {
        abort_unless(CurrentClass::membership(), 403);
        ClassNotification::whereNull('read_at')->update(['read_at' => now()]);
    }

    public function open(int $id): void
    {
        $notification = ClassNotification::findOrFail($id);
        $notification->update(['read_at' => now()]);
        if ($notification->kind === 'announcement') {
            $this->redirectRoute('announcements.show', ['post' => $notification->post_id]);
        } else {
            $this->redirectRoute('feed.show', ['postId' => $notification->post_id, 'comments' => $notification->kind === 'comment' ? 1 : 0]);
        }
    }

    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);
        $query = ClassNotification::with('post');
        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        }

        return view('livewire.notifications.index', [
            'notifications' => $query->latest('id')->paginate(12),
            'unread' => ClassNotification::whereNull('read_at')->count(),
        ]);
    }
}
