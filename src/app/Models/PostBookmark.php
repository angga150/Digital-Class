<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostBookmark extends Model
{
    protected $fillable = ['post_id', 'user_id'];

    protected static function booted(): void
    {
        static::addGlobalScope('owner', fn (Builder $query) => $query->where('post_bookmarks.user_id', auth()->id() ?? 0)->whereHas('post'));
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
