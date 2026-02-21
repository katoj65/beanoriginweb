<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchChainEvent extends Model
{
    protected $table = 'batch_chain_events';

    protected $fillable = [
        'batch_id',
        'chain_block_id',
        'event_type',
        'event_data',
        'status',
        'tx_hash',
        'occurred_at',
    ];

    protected $casts = [
        'event_data' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function chainBlock(): BelongsTo
    {
        return $this->belongsTo(ChainBlock::class, 'chain_block_id');
    }
}

