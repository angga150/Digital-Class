<?php

namespace App\Livewire\Announcements;

use App\Models\Post;
use App\Support\CurrentClass;
use Livewire\Component;

class Featured extends Component
{
    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);

        return view('livewire.announcements.featured', [
            'post' => Post::where('type', 'announcement')->orderByDesc('is_pinned')->latest('created_at')->latest('id')->first(),
        ]);
    }
}
