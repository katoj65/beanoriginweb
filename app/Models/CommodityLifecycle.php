<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommodityLifecycle extends Model
{
    protected $table = 'commodity_lifecycles';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function activities(): HasMany
    {
        return $this->hasMany(CommodityLifecycleActivity::class, 'commodity_lifecycle_id');
    }
}

