<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
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
            'owner_id' => $this->owner_id,
            'batch_code' => $this->batch_code,
            'commodity_name' => $this->commodity_name,
            'commodity_type' => $this->commodity_type,
            'weight' => $this->weight,
            'grade' => $this->grade,
            'moisture' => $this->moisture,
            'warehouse' => $this->warehouse,
            'is_on_chain' => (bool) $this->is_on_chain,
            'status' => $this->status,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'owner' => $this->whenLoaded('owner', function () {
                return [
                    'id' => $this->owner?->id,
                    'name' => trim(($this->owner?->fname ?? '') . ' ' . ($this->owner?->lname ?? '')),
                    'email' => $this->owner?->email,
                ];
            }),
        ];
    }
}
