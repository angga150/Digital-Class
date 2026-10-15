<?php

namespace App\Models;

use App\Support\CurrentClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope('current_class', fn (Builder $query) => $query->where('events.class_id', CurrentClass::membership()?->class_id ?? 0));
    }

    public function getStatusAttribute(): string
    {
        if ($this->cancelled_at) {
            return 'cancelled';
        }
        if ($this->event_date->isFuture()) {
            return 'upcoming';
        }

        return $this->ends_at?->isFuture() ? 'ongoing' : 'completed';
    }

    public function getStatusLabelAttribute(): string
    {
        return ['upcoming' => 'Akan datang', 'ongoing' => 'Sedang berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'][$this->status];
    }

    public function getCountdownAttribute(): string
    {
        if ($this->status !== 'upcoming') {
            return $this->status_label;
        }
        $minutes = (int) ceil(now()->diffInMinutes($this->event_date));
        if ($minutes >= 1440) {
            return (int) ceil($minutes / 1440).' hari lagi';
        }

        return $minutes >= 60 ? intdiv($minutes, 60).' jam '.($minutes % 60).' menit lagi' : max(1, $minutes).' menit lagi';
    }

    public function scopeForStatus(Builder $query, string $status): void
    {
        $now = now();
        match ($status) {
            'upcoming' => $query->whereNull('cancelled_at')->where('event_date', '>', $now),
            'ongoing' => $query->whereNull('cancelled_at')->where('event_date', '<=', $now)->where('ends_at', '>', $now),
            'completed' => $query->whereNull('cancelled_at')->where('event_date', '<=', $now)->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '<=', $now)),
            'cancelled' => $query->whereNotNull('cancelled_at'),
            default => $query,
        };
    }

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
        'ends_at',
        'cancelled_at',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
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
        if (! $user) {
            return false;
        }

        return $this->rsvps()->where('user_id', $user->id)->exists();
    }

    public function getRemainingDaysAttribute(): int
    {
        return (int) max(0, now()->diffInDays($this->event_date, false));
    }

    public function getRemainingHoursAttribute(): int
    {
        $diff = (int) now()->diffInHours($this->event_date, false);

        return max(0, $diff % 24);
    }

    public function getRemainingMinutesAttribute(): int
    {
        $diff = (int) now()->diffInMinutes($this->event_date, false);

        return max(0, $diff % 60);
    }
}
