<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commodity extends Model
{
    protected $table = 'commodities';

    protected $fillable = [
        'cooperative_id',
        'commodity_name',
        'commodity_type',
        'grade',
        'weight',
        'price',
        'ripe_percentage',
        'density_percentage',
        'harvest_date',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'ripe_percentage' => 'integer',
        'density_percentage' => 'integer',
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

    public function farms(): BelongsToMany
    {
        return $this->belongsToMany(
            Farm::class,
            'commodity_farms',
            'commodity_id',
            'farm_id'
        )->using(CommodityFarm::class)
            ->withTimestamps();
    }

    public function chainBatches(): BelongsToMany
    {
        return $this->belongsToMany(
            ChainBatch::class,
            'commodity_batches',
            'commodity_id',
            'chain_batch_id'
        )->using(CommodityBatch::class)
            ->withPivot(['weight', 'status'])
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CommodityPayment::class, 'commodity_id');
    }
}
