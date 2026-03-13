<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchBidsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $batchCode = $this->batch_code ?? $this->batch?->batch_code;
        $commodityName = $this->commodity_name ?? $this->batch?->commodity_name;
        $commodityType = $this->commodity_type ?? $this->batch?->commodity_type;
        $grade = $this->grade ?? $this->batch?->grade;
        $weight = $this->weight ?? $this->batch?->weight;
        $availableQuantity = $this->available_quantity ?? $this->batch?->quantity;
        $askPrice = $this->ask_price ?? $this->batch?->price;
        $warehouse = $this->warehouse ?? $this->batch?->warehouse;

        return [
            'id' => $this->id,
            'batch_id' => $this->batch_id,
            'user_id' => $this->user_id,
            'batch_code' => $batchCode,
            'commodity_name' => $commodityName,
            'commodity_type' => $commodityType,
            'grade' => $grade,
            'weight' => $weight,
            'available_quantity' => $availableQuantity,
            'ask_price' => $askPrice,
            'warehouse' => $warehouse,
            'bid_quantity' => $this->bid_quantity,
            'bid_price' => $this->bid_price,
            'bid_notes' => $this->bid_notes,
            'status' => $this->status,
            'currency' => 'UGX',
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'batch' => $this->whenLoaded('batch', function () {
                return [
                    'id' => $this->batch?->id,
                    'batch_code' => $this->batch?->batch_code,
                    'commodity_name' => $this->batch?->commodity_name,
                    'commodity_type' => $this->batch?->commodity_type,
                    'grade' => $this->batch?->grade,
                    'quantity' => $this->batch?->quantity,
                    'price' => $this->batch?->price,
                    'warehouse' => $this->batch?->warehouse,
                ];
            }),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user?->id,
                    'fname' => $this->user?->fname,
                    'lname' => $this->user?->lname,
                    'email' => $this->user?->email,
                ];
            }),
        ];
    }
}
