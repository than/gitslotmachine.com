<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecretDiscovery extends Model
{
    protected $fillable = [
        'secret_hash',
        'secret_name',
        'play_id',
        'user_id',
        'discovered_at',
    ];

    protected function casts(): array
    {
        return [
            'discovered_at' => 'datetime',
        ];
    }

    public function play(): BelongsTo
    {
        return $this->belongsTo(Play::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
