<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchTradeActivityMetadata extends Model
{
    protected $table = 'batch_trade_activity_metadata';

    protected $fillable = [
        'activity',
    ];
}
