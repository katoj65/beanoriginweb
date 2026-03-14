<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchTradeActivityData extends Model
{
    protected $table = 'batch_trade_activity_data';

    protected $fillable = [
        'batch_id',
        'activity',
        'value',
    ];

    protected $casts = [
        'batch_id' => 'integer',
        'activity' => 'string',
        'value' => 'string',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
