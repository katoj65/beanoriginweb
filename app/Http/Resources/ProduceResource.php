<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduceResource extends JsonResource
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
            'farm_id' => $this->farm_id,
            'cooperative_id' => $this->user_id,
            'crop_name' => $this->crop_name,
            'crop_type' => $this->crop_type,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'location' => $this->location,
            'date_of_harvest' => $this->date_of_havest?->format('Y-m-d'),
            'crop_grade' => $this->crop_grade,
            'process_method' => $this->process_method,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
