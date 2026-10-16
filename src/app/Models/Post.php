<?php

namespace App\Models;

use App\Support\CurrentClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope('current_class', function (Builder $query) {
            $query->where('posts.class_id', CurrentClass::membership()?->class_id ?? 0);
        });
    }

    protected $fillable = [
        'class_id',
        'user_id',
        'content',
        'image',
        'type',
        'is_pinned',
        'likes_count',
        'comments_count',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
    ];

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(PostBookmark::class);
    }

    public function isLikedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
