<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Play extends Model
{
    protected $fillable = [
        'user_id',
        'repository_id',
        'commit_hash',
        'pattern_type',
        'pattern_name',
        'payout',
        'repo_balance_after',
        'played_at',
    ];

    protected function casts(): array
    {
        return [
            'payout' => 'integer',
            'repo_balance_after' => 'integer',
            'played_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }
}
