<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassMember extends Model
{
    protected $fillable = [
        'class_id',
        'user_id',
        'role',
        'nickname',
        'birthday',
        'student_id',
        'attendance_count',
        'total_sessions',
        'joined_at',
    ];

    protected $casts = [
        'birthday' => 'date',
        'joined_at' => 'datetime',
        'attendance_count' => 'integer',
        'total_sessions' => 'integer',
    ];

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAttendancePercentageAttribute(): float
    {
        if ($this->total_sessions === 0) {
            return 0;
        }
        return round(($this->attendance_count / $this->total_sessions) * 100, 1);
    }

    public function isBirthdayToday(): bool
    {
        if (!$this->birthday) {
            return false;
        }
        
        return $this->birthday->format('m-d') === now()->format('m-d');
    }
}
