<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchBid extends Model
{
    protected $table = 'batch_bids';

    protected $fillable = [
        'batch_id',
        'user_id',
        'bid_quantity',
        'bid_price',
        'bid_notes',
        'status',
    ];

    protected $casts = [
        'batch_id' => 'integer',
        'user_id' => 'integer',
        'bid_quantity' => 'decimal:2',
        'bid_price' => 'decimal:2',
        'status' => 'string',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

