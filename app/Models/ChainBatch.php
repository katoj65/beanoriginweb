<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChainBatch extends Model
{
    protected $table = 'chain_batches';
    protected $fillable = [
        'user_id',
        'batch_number',
        'grade',
        'weight',
        'status',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
