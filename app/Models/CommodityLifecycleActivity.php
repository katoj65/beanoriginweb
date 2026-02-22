<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommodityLifecycleActivity extends Model
{
    protected $table = 'commodity_lifecycle_activities';

    protected $fillable = [
        'commodity_lifecycle_id',
        'activity',
        'description',
    ];

    public function commodityLifecycle(): BelongsTo
    {
        return $this->belongsTo(CommodityLifecycle::class, 'commodity_lifecycle_id');
    }
}
