<?php

namespace App\Models;

use App\Support\CurrentClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope('current_class', fn (Builder $query) => $query->where('polls.class_id', CurrentClass::membership()?->class_id ?? 0));
    }

    protected $fillable = [
        'class_id',
        'created_by',
        'question',
        'description',
        'allow_multiple',
        'is_anonymous',
        'ends_at',
        'is_active',
        'total_votes',
        'result_visibility',
    ];

    protected $casts = [
        'allow_multiple' => 'boolean',
        'is_anonymous' => 'boolean',
        'is_active' => 'boolean',
        'ends_at' => 'datetime',
        'total_votes' => 'integer',
    ];

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class)->orderBy('order');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function hasVoted(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->votes()->where('user_id', $user->id)->exists();
    }

    public function isExpired(): bool
    {
        return $this->ends_at && now()->greaterThanOrEqualTo($this->ends_at);
    }

    public function isOpen(): bool
    {
        return $this->is_active && ! $this->isExpired() && ! $this->allow_multiple;
    }
}
