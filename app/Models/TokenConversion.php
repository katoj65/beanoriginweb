<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenConversion extends Model
{
    protected $fillable = [
        'token_id',
        'weight',
        'price',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class, 'token_id');
    }
}
