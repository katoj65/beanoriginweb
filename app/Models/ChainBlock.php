<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChainBlock extends Model
{
    protected $table = 'chain_blocks';

    protected $fillable = [
        'batch_id',
        'chain_batches_id',
        'block_index',
        'event_type',
        'event_data',
        'current_hash',
        'previous_hash',
    ];

    protected $casts = [
        'event_data' => 'array',
    ];

    public function chainBatch(): BelongsTo
    {
        return $this->belongsTo(ChainBatch::class, 'chain_batches_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(BatchChainEvent::class, 'chain_block_id');
    }
}
