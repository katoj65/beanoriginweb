<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'batch_id' => $this->batch_id,
            'buyer_id' => $this->buyer_id,
            'seller_id' => $this->seller_id,
            'shopping_cart_session' => $this->shopping_cart_session,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'transaction_reference' => $this->transaction_reference,
            'status' => $this->status,
            'notes' => $this->notes,
            'address' => $this->address,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
