<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommodityPaymentResource extends JsonResource
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
            'commodity_id' => $this->commodity_id,
            'buyer_id' => $this->buyer_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),

            'commodity' => $this->whenLoaded('commodity', function () {
                return [
                    'id' => $this->commodity?->id,
                    'commodity_name' => $this->commodity?->commodity_name,
                    'commodity_type' => $this->commodity?->commodity_type,
                ];
            }),
            'buyer' => $this->whenLoaded('buyer', function () {
                return [
                    'id' => $this->buyer?->id,
                    'name' => trim(($this->buyer?->fname ?? '') . ' ' . ($this->buyer?->lname ?? '')),
                    'email' => $this->buyer?->email,
                ];
            }),
        ];
    }
}
