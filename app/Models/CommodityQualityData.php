<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommodityQualityData extends Model
{
    protected $table = 'commodity_quality_data';

    protected $fillable = ['commodity_id', 'activity', 'value'];

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }
}

