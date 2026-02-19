<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommoditychainResource extends JsonResource
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
            'produces_id' => $this->produces_id,
            'cooperative_id' => $this->cooperative_id,
            'farm_id' => $this->farm_id,
            'commodity_name' => $this->commodity_name,
            'commodity_type' => $this->commodity_type,
            'grade' => $this->grade,
            'weight' => $this->weight,
            'harvest_date' => optional($this->harvest_date)->format('Y-m-d'),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),

            'produce' => $this->whenLoaded('produce', function () {
                return [
                    'id' => $this->produce?->id,
                    'crop_name' => $this->produce?->crop_name,
                    'crop_type' => $this->produce?->crop_type,
                    'quantity' => $this->produce?->quantity,
                    'status' => $this->produce?->status,
                ];
            }),
            'cooperative' => $this->whenLoaded('cooperative', function () {
                return [
                    'id' => $this->cooperative?->id,
                    'name' => $this->cooperative?->name,
                    'legal_name' => $this->cooperative?->legal_name,
                ];
            }),
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
