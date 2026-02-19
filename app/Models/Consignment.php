<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consignment extends Model
{
    protected $table = 'consignments';

    protected $fillable = [
        'consignment_ref',
        'lot_id',
        'buyer_id',
        'transporter_name',
        'vehicle_reg',
        'container_no',
        'total_net_weight',
        'total_gross_weight',
        'dispatched_at',
        'estimated_delivery',
        'status',
    ];

    protected $casts = [
        'total_net_weight' => 'decimal:2',
        'total_gross_weight' => 'decimal:2',
        'dispatched_at' => 'datetime',
        'estimated_delivery' => 'date',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class, 'lot_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
