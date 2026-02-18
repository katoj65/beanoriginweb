<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerBatchVerification extends Model
{
    protected $table = 'farmer_batch_verifications';

    protected $fillable = [
        'cooperative_id',
        'cooperative_farmers_id',
        'verification_code',
        'verification_id',
        'expiry_minutes',
        'status',
    ];

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id');
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(CooperativeFarmer::class, 'cooperative_farmers_id');
    }
}
