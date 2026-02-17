<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produce extends Model
{
    protected $fillable = [
        'farm_id',
        'cooperative_id',
        'crop_name',
        'crop_type',
        'quantity',
        'price',
        'location',
        'date_of_harvest',
        'crop_grade',
        'process_method',
        'status',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
        'date_of_harvest' => 'date',
    ];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
