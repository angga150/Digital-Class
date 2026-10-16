<?php

namespace App\Models;

use App\Support\CurrentClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassNotification extends Model
{
    protected $fillable = ['read_at'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('recipient', function (Builder $query) {
            $query->where('class_notifications.user_id', auth()->id() ?? 0)
                ->where('class_notifications.class_id', CurrentClass::membership()?->class_id ?? 0)
                ->whereHas('post', fn ($post) => $post->whereColumn('posts.class_id', 'class_notifications.class_id'))
                ->where(function ($query) {
                    $query->where(fn ($q) => $q->where('kind', 'announcement')->whereHas('post', fn ($post) => $post->where('type', 'announcement')))
                        ->orWhere(fn ($q) => $q->where('kind', 'comment')->whereHas('comment', fn ($comment) => $comment->whereColumn('post_comments.post_id', 'class_notifications.post_id')))
                        ->orWhere(fn ($q) => $q->where('kind', 'like')->whereHas('post.likes', fn ($like) => $like->whereColumn('post_likes.user_id', 'class_notifications.actor_id')));
                });
        });
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(PostComment::class, 'comment_id');
    }

    public function getLabelAttribute(): string
    {
        return match ($this->kind) {
            'comment' => 'Ada komentar baru di ceritamu',
            'like' => 'Seseorang menyukai ceritamu',
            default => 'Pengumuman baru untuk kelasmu',
        };
    }
}
