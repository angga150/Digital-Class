<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassModel extends Model
{
    protected $table = 'classes';
    
    protected $fillable = [
        'name',
        'code',
        'major',
        'batch',
        'section',
        'description',
        'join_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(ClassMember::class, 'class_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'class_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'class_id');
    }

    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class, 'class_id');
    }
}
