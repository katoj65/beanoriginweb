<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Token extends Model
{
    protected $table = 'token';

    protected $fillable = [
        'block_id',
        'token_index',
        'token_hash',
        'previous_hash',
        'weight',
        'price',
        'event_data',
        'event_type',
        'status',
        'current_owner',
        'previous_owner',
    ];

    protected $casts = [
        'event_data' => 'array',
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class, 'block_id');
    }

    public function currentOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_owner');
    }

    public function previousOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'previous_owner');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(TokenActivity::class, 'token_id');
    }
}
