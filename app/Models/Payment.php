<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'shopping_cart_session',
        'transaction_reference',
        'number_of_items',
        'quantity',
        'currency',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'number_of_items' => 'integer',
        'quantity' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'status' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
