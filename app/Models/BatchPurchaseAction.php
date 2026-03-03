<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchPurchaseAction extends Model
{
    protected $table = 'batch_purchase_actions';

    protected $fillable = [
        'name',
    ];
}
