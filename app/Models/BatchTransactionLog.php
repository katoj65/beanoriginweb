<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchTransactionLog extends Model
{
    protected $table = 'batch_transaction_logs';

    protected $fillable = [
        'batch_id',
        'block_id',
        'block_purchase_id',
        'buyer_id',
        'seller_id',
        'purchase_price',
        'currency',
        'previous_status',
        'new_status',
        'event_type',
        'notes',
        'meta',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'meta' => 'array',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class, 'block_id');
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(BlockPurchase::class, 'block_purchase_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}

