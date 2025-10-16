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
        'moderation_status',
        'moderated_at',
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
            'moderated_at' => 'datetime',
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

    /**
     * Check if the user is approved for display
     */
    public function isApproved(): bool
    {
        return $this->moderation_status === 'approved';
    }

    /**
     * Check if the user is pending moderation
     */
    public function isPending(): bool
    {
        return $this->moderation_status === 'pending';
    }

    /**
     * Check if the user is rejected
     */
    public function isRejected(): bool
    {
        return $this->moderation_status === 'rejected';
    }

    /**
     * Get the display name for the user (respects moderation status)
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->isApproved()) {
            return $this->github_username;
        }

        return '*******';
    }

    /**
     * Approve the user
     */
    public function approve(): void
    {
        $this->update([
            'moderation_status' => 'approved',
            'moderated_at' => now(),
        ]);
    }

    /**
     * Reject the user
     */
    public function reject(): void
    {
        $this->update([
            'moderation_status' => 'rejected',
            'moderated_at' => now(),
        ]);
    }
}
