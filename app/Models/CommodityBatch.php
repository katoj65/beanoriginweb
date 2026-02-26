<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CommodityBatch extends Pivot
{
    protected $table = 'commodity_batches';

    public $incrementing = false;

    protected $fillable = [
        'commodity_id',
        'batch_id',
    ];


    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
