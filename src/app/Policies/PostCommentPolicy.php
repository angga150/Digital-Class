<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use App\Support\CurrentClass;

class PostCommentPolicy
{
    public function create(User $user, Post $post): bool
    {
        return (new PostPolicy)->interact($user, $post);
    }

    public function delete(User $user, PostComment $comment): bool
    {
        return ! $comment->trashed() && $comment->post
            && (new PostPolicy)->interact($user, $comment->post)
            && ($user->id === $comment->user_id || CurrentClass::membership($user)?->role === 'admin');
    }
}
