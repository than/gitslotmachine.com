<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'github_username',
        'total_balance',
        'total_commits',
        'biggest_win',
        'biggest_win_pattern',
        'biggest_win_hash',
        'current_streak',
        'longest_streak',
        'longest_streak_ended_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'total_balance' => 'integer',
            'total_commits' => 'integer',
            'biggest_win' => 'integer',
            'current_streak' => 'integer',
            'longest_streak' => 'integer',
            'longest_streak_ended_at' => 'datetime',
        ];
    }

    public function repositories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Repository::class);
    }

    public function plays(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Play::class);
    }
}
