<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchQualityData extends Model
{
    //
    protected $table='batch_quality_data';
    protected $fillable = ['batch_id','activity','value'];

    protected function batch():BelongsTo
    {
        return $this->belongsTo(Batch::class,'batch_id');
    }

}
