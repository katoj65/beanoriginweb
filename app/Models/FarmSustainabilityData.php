<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmSustainabilityData extends Model
{
    protected $table = 'farm_sustainability_data';

    protected $fillable = [
        'farm_id',
        'activity',
        'value'
    ];

    protected $casts = [
        'farm_id' => 'integer',
        'activity'=>'string',
        'value'=>'string'
    ];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class, 'farm_id');
    }
}

