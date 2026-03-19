<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchLabMetric extends Model
{
    protected $table = 'batch_lab_metrics';

    protected $fillable = [
        'batch_id',
        'metric',
        'value',
        'notes',
    ];

    protected $casts = [
        'batch_id' => 'integer',
        'metric' => 'string',
        'value' => 'string',
        'notes' => 'string',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
