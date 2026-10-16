<?php

namespace App\Livewire\Notifications;

use App\Models\ClassNotification;
use Livewire\Component;

class Bell extends Component
{
    public function render()
    {
        return view('livewire.notifications.bell', ['unread' => ClassNotification::whereNull('read_at')->count()]);
    }
}
