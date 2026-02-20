<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChainBatchStatus extends Model
{
    protected $table = 'chain_batch_statuses';

    protected $fillable = [
        'name',
    ];

    public function chainBatches(): HasMany
    {
        return $this->hasMany(ChainBatch::class, 'status', 'name');
    }
}
