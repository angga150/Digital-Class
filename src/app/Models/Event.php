<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'class_id',
        'created_by',
        'title',
        'description',
        'type',
        'event_date',
        'location',
        'link',
        'rsvp_count',
        'is_featured',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_featured' => 'boolean',
        'rsvp_count' => 'integer',
    ];

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    public function hasRsvp(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        
        return $this->rsvps()->where('user_id', $user->id)->exists();
    }

    public function getRemainingDaysAttribute(): int
    {
        return max(0, now()->diffInDays($this->event_date, false));
    }

    public function getRemainingHoursAttribute(): int
    {
        $diff = now()->diffInHours($this->event_date, false);
        return max(0, $diff % 24);
    }

    public function getRemainingMinutesAttribute(): int
    {
        $diff = now()->diffInMinutes($this->event_date, false);
        return max(0, $diff % 60);
    }
}
