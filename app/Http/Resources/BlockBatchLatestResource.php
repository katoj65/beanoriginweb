<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlockBatchLatestResource extends JsonResource
{
/**
 * Transform the resource into an array.
 *
 * @return array<string, mixed>
 */
public function toArray(Request $request): array
{
    $eventData = [];
    if (is_array($this->event_data)) {
        $eventData = $this->event_data;
    } elseif (is_string($this->event_data) && $this->event_data !== '') {
        $decoded = json_decode($this->event_data, true);
        $eventData = is_array($decoded) ? $decoded : [];
    }

return [
    'batch_id' => $this->batch_id ?? $this->id,
    'commodity_name' => $this->commodity_name,
    'batch_code' => $this->batch_code,
    'created_at' => $this->created_at?->toDateString(),
    'price' => $this->price,
    'weight' => $this->weight,
    'event_type' => $this->event_type,
    'status' => $this->status ?? 'active',
    'event_data' => $eventData,
    'grade' => $eventData['grade'] ?? $this->grade,
];
}
}
