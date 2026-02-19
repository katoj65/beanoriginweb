<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lot extends Model
{
    protected $table = 'lots';

    protected $fillable = [
        'cooperative_id',
        'lot_number',
        'weight',
        'quality_summary',
        'price',
        'status',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id');
    }
}
