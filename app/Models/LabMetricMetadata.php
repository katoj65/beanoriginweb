<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabMetricMetadata extends Model
{
    protected $table = 'lab_metric_metadata';

    protected $fillable = [
        'name',
    ];
}
