<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Batch extends Model
{
    protected $table = 'batches';

    protected $fillable = [
        'cooperative_id',
        'farm_id',
        'batch_code',
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

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id');
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class, 'farm_id');
    }
}
