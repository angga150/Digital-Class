<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = ['nickname', 'avatar_path', 'bio', 'hobby', 'favorite', 'fun_fact', 'birth_date', 'birth_year_visible', 'birthday_visibility', 'profile_visibility'];

    protected function casts(): array
    {
        return ['hobby' => 'array', 'favorite' => 'array', 'birth_date' => 'date', 'birth_year_visible' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
