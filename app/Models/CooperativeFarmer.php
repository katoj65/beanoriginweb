<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CooperativeFarmer extends Model
{
    protected $table = 'cooperative_farmers';

    protected $fillable = [
        'cooperative_id',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'gender',
        'date_of_birth',
        'national_id',
        'district',
        'sub_county',
        'village',
        'primary_crop',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }
}
