<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope('current_class', fn (Builder $query) => $query->whereHas('poll'));
    }

    protected $fillable = [
        'poll_id',
        'option_text',
        'votes_count',
        'order',
    ];

    protected $casts = [
        'votes_count' => 'integer',
        'order' => 'integer',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function getPercentageAttribute(): float
    {
        if ($this->poll->total_votes === 0) {
            return 0;
        }

        return round(($this->votes_count / $this->poll->total_votes) * 100, 1);
    }
}
