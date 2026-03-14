<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Token extends Model
{
    protected $table = 'token';

    protected $fillable = [
        'batch_id',
        'token_index',
        'event_type',
        'metadata',
        'current_hash',
        'previous_hash',
        'current_owner',
        'previous_owner',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function currentOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_owner');
    }

    public function previousOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'previous_owner');
    }

    public function conversions(): HasMany
    {
        return $this->hasMany(TokenConversion::class, 'token_id');
    }
}
