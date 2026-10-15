<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->profile?->nickname ?: $this->name;
    }

    public function getInitialsAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->display_name, 0, 1));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function classMemberships(): HasMany
    {
        return $this->hasMany(ClassMember::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function postLikes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function postComments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function eventRsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    public function createdPolls(): HasMany
    {
        return $this->hasMany(Poll::class, 'created_by');
    }

    public function pollVotes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }
}
