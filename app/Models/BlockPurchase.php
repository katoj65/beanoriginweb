<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockPurchase extends Model
{
    protected $table = 'block_purchases';

    protected $fillable = [
        'block_id',
        'purchase_price',
        'currency',
        'buyer_id',
        'seller_id',
        'transaction_reference',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
    ];

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class, 'block_id');
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
