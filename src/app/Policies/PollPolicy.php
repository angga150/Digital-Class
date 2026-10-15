<?php

namespace App\Policies;

use App\Models\Poll;
use App\Models\User;
use App\Support\CurrentClass;

class PollPolicy
{
    public function view(User $user, Poll $poll): bool
    {
        return CurrentClass::membership($user)?->class_id === $poll->class_id;
    }

    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail() && CurrentClass::membership($user)?->role === 'admin';
    }

    public function close(User $user, Poll $poll): bool
    {
        return $this->view($user, $poll) && $this->create($user) && $poll->isOpen();
    }

    public function vote(User $user, Poll $poll): bool
    {
        return $this->view($user, $poll) && $user->hasVerifiedEmail() && $poll->isOpen();
    }

    public function viewResults(User $user, Poll $poll): bool
    {
        if (! $this->view($user, $poll)) {
            return false;
        }

        return match ($poll->result_visibility) {
            'all' => true,
            'after_vote' => ! $poll->isOpen() || $poll->hasVoted($user),
            'closed' => ! $poll->isOpen(),
            'admin_only' => CurrentClass::membership($user)?->role === 'admin',
            default => false,
        };
    }
}
