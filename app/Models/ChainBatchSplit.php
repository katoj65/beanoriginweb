<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChainBatchSplit extends Model
{
    protected $table = 'chain_batch_splits';

    protected $fillable = [
        'chain_batches_id',
        'user_id',
        'weight',
        'reason',
        'notes',

    ];

    protected $casts = [
        'weight' => 'decimal:2',
    ];

    public function sourceBatch(): BelongsTo
    {
        return $this->belongsTo(ChainBatch::class, 'chain_batches_id');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
