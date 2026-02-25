<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;
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
        return [
            'id' => $this->id,
            'cooperative_id' => $this->cooperative_id,
            'commodity_name' => $this->commodity_name,
            'commodity_type' => $this->commodity_type,
            'grade' => $this->grade,
            'weight' => $this->weight,
            'price' => $this->price,
            'harvest_date' => $this->harvest_date
                ? Carbon::parse($this->harvest_date)->format('F j, Y')
                : null,
            'status' => $this->status,
            'created_at' => $this->created_at
                ? Carbon::parse($this->created_at)->format('D, F j, Y g:i A')
                : null,
            'latest_payment' => $this->whenLoaded('payments', function () {
                $latest = $this->payments->first();
                return $latest ? new CommodityPaymentResource($latest) : null;
            }),
            'payments' => $this->whenLoaded(
                'payments',
                fn () => CommodityPaymentResource::collection($this->payments)
            ),
        ];
    }
}
