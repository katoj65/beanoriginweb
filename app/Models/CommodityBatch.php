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
        'chain_batch_id',
        'status',
    ];


    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }

    public function chainBatch(): BelongsTo
    {
        return $this->belongsTo(ChainBatch::class, 'chain_batch_id');
    }
}
