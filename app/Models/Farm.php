<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Farm extends Model
{
    protected $fillable = [
        'cooperative_farmer_id',
        'farm_name',
        'location',
        'latitude',
        'longitude',
        'area_acres',
        'primary_crop',
        'soil_type',
        'water_source_type',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'area_acres' => 'decimal:2',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(CooperativeFarmer::class, 'cooperative_farmer_id');
    }
}
