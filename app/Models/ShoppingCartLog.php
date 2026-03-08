<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingCartLog extends Model
{
    protected $table = 'shopping_cart_logs';

    protected $fillable = [
        'shopping_cart_id',
        'user_id',
        'batch_id',
        'shopping_session',
        'action',
        'quantity_before',
        'quantity_after',
        'status_before',
        'status_after',
        'notes',
        'meta',
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
        'meta' => 'array',
    ];

    public function shoppingCart(): BelongsTo
    {
        return $this->belongsTo(ShoppingCart::class, 'shopping_cart_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
