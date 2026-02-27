<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchStatusList extends Model
{
    protected $table = 'batch_status_list';

    protected $fillable = [
        'name',
    ];
}
