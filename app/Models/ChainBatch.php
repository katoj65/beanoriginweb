<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChainBatch extends Model
{
    protected $table = 'chain_batches';
    protected $fillable = [
        'user_id',
        'batch_number',
        'grade',
        'weight',
        'status',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(ChainBlock::class, 'chain_batches_id');
    }

    public function batchStatus(): BelongsTo
    {
        return $this->belongsTo(ChainBatchStatus::class, 'status', 'name');
    }

    public function outgoingSplits(): HasMany
    {
        return $this->hasMany(ChainBatchSplit::class, 'chain_batches_id');
    }

    public function incomingSplits(): HasMany
    {
        return $this->hasMany(ChainBatchSplit::class, 'split_chain_batches_id');
    }

    public function commodities(): BelongsToMany
    {
        return $this->belongsToMany(
            Commodity::class,
            'commodity_batches',
            'chain_batch_id',
            'commodity_id'
        )->using(CommodityBatch::class)
            ->withPivot(['weight', 'status'])
            ->withTimestamps();
    }
}
