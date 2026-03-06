<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceIndex extends Model
{
    protected $table = 'price_index';

    protected $fillable = [
        'commodity',
        'type',
        'grade',
        'min_price',
        'max_price',
        'quantity',
    ];

    protected $casts = [
        'min_price' => 'float',
        'max_price' => 'float',
        'quantity' => 'decimal:2',
    ];
}
