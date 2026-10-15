<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use App\Support\CurrentClass;

class EventPolicy
{
    public function view(User $user, Event $event): bool
    {
        return ! $event->trashed() && CurrentClass::membership($user)?->class_id === $event->class_id;
    }

    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail() && CurrentClass::membership($user)?->role === 'admin';
    }

    public function update(User $user, Event $event): bool
    {
        return $this->view($user, $event) && $this->create($user) && $event->status === 'upcoming';
    }

    public function cancel(User $user, Event $event): bool
    {
        return $this->view($user, $event) && $this->create($user) && in_array($event->status, ['upcoming', 'ongoing'], true);
    }

    public function delete(User $user, Event $event): bool
    {
        return $this->view($user, $event) && $this->create($user);
    }

    public function respond(User $user, Event $event): bool
    {
        return $this->view($user, $event) && $user->hasVerifiedEmail() && $event->status === 'upcoming';
    }
}
