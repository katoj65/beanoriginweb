<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommodityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $latestPayment = $this->whenLoaded('payments', function () {
            return $this->payments->sortByDesc('created_at')->first();
        });

        return [
            'id' => $this->id,
            'cooperative_id' => $this->cooperative_id,
            'farm_id' => $this->farm_id,
            'commodity_name' => $this->commodity_name,
            'commodity_type' => $this->commodity_type,
            'grade' => $this->grade,
            'weight' => $this->weight,
            'harvest_date' => optional($this->harvest_date)->format('Y-m-d'),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),

            // Backward-compatible aliases for existing Produce-style UI keys.
            'crop_name' => $this->commodity_name,
            'crop_type' => $this->commodity_type,
            'quantity' => $this->weight,
            'price' => $latestPayment?->unit_price,
            'location' => $this->whenLoaded('farm', fn () => $this->farm?->location),
            'date_of_harvest' => optional($this->harvest_date)->format('Y-m-d'),
            'crop_grade' => $this->grade,
            'process_method' => null,
            'status' => $latestPayment?->status ?? 'pending',
            'latest_payment' => $latestPayment ? new CommodityPaymentResource($latestPayment) : null,
            'payments' => CommodityPaymentResource::collection($this->whenLoaded('payments')),

            'farm' => $this->whenLoaded('farm', function () {
                return [
                    'id' => $this->farm?->id,
                    'farm_name' => $this->farm?->farm_name,
                    'location' => $this->farm?->location,
                    'primary_crop' => $this->farm?->primary_crop,
                ];
            }),
        ];
    }
}
