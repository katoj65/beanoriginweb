<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityLifecycle extends Model
{
    //
    protected $table = 'commodity_lifecycles';
    protected $fillable = [
        'name',
        'description',
    ];
}



