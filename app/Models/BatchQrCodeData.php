<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchQrCodeData extends Model
{
    protected $table = 'batch_qr_code_data';

    protected $fillable = [
        'batch_id',
        'qr_code',
        'scan_url',
        'metadata',
    ];

    protected $casts = [
        'batch_id' => 'integer',
        'qr_code' => 'string',
        'scan_url' => 'string',
        'metadata' => 'array',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
