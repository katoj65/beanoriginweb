<?php

namespace App\Services\Payments;

use App\Models\CommodityPayment;

class PaymentService
{
    public static function commodity_payment(array $data): CommodityPayment
    {
        return CommodityPayment::create([
            'commodity_id' => $data['commodity_id'],
            'buyer_id' => $data['buyer_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'total_amount' => $data['quantity'] * $data['unit_price'],
            'status' => $data['status'] ?? 'pending',
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
