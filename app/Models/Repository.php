<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repository extends Model
{
    protected $fillable = [
        'user_id',
        'owner',
        'name',
        'github_url',
        'balance',
        'total_commits',
        'last_commit_hash',
        'last_played_at',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'integer',
            'total_commits' => 'integer',
            'last_played_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plays(): HasMany
    {
        return $this->hasMany(Play::class);
    }
}
