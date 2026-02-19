<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommodityChain extends Model
{
    protected $table = 'commodity_chains';

    protected $fillable = [
        'produces_id',
        'cooperative_id',
        'farm_id',
        'commodity_name',
        'commodity_type',
        'grade',
        'weight',
        'harvest_date',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'harvest_date' => 'date',
    ];

    public function produce(): BelongsTo
    {
        return $this->belongsTo(Produce::class, 'produces_id');
    }

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id');
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class, 'farm_id');
    }
}
