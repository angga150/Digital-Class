<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Support\CurrentClass;

class PostPolicy
{
    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail() && CurrentClass::membership($user) !== null;
    }

    public function interact(User $user, Post $post): bool
    {
        return $this->create($user) && ! $post->trashed()
            && CurrentClass::membership($user)?->class_id === $post->class_id;
    }

    public function update(User $user, Post $post): bool
    {
        return $this->interact($user, $post) && $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->interact($user, $post)
            && ($user->id === $post->user_id || CurrentClass::membership($user)?->role === 'admin');
    }
}
