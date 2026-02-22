<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    protected $table = 'batches';

    protected $fillable = [
        'owner_id',
        'batch_code',
        'commodity_name',
        'commodity_type',
        'weight',
        'grade',
        'moisture',
        'warehouse',
        'is_on_chain',
        'status',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'moisture' => 'decimal:2',
        'is_on_chain' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function user(): BelongsTo
    {
        return $this->owner();
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(ChainBlock::class, 'batch_id');
    }

    public function chainEvents(): HasMany
    {
        return $this->hasMany(BatchChainEvent::class, 'batch_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(BatchAction::class, 'batch_id');
    }
}
