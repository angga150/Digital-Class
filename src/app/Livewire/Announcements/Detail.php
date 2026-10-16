<?php

namespace App\Livewire\Announcements;

use App\Models\Post;
use App\Support\CurrentClass;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.member')]
#[Title('Pengumuman Kelas — Digital Class')]
class Detail extends Component
{
    #[Locked]
    public int $postId;

    public function mount(Post $post): void
    {
        abort_unless($post->type === 'announcement', 404);
        $this->postId = $post->id;
    }

    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);

        return view('livewire.announcements.detail', [
            'post' => Post::where('type', 'announcement')->findOrFail($this->postId),
        ]);
    }
}
