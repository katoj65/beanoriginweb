<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $createdAt = $this->created_at?->toDateTimeString() ?? $this->getRawOriginal('created_at');
        $updatedAt = $this->updated_at?->toDateTimeString() ?? $this->getRawOriginal('updated_at');

        return [
            'id' => $this->id,
            'batch_id' => $this->batch_id,
            'block_index' => $this->block_index,
            'price'=> $this->price,
            'event_type' => $this->event_type,
            'current_hash' => $this->current_hash,
            'previous_hash' => $this->previous_hash,
            'current_owner' => $this->current_owner,
            'previous_owner' => $this->previous_owner,
            'weight' => $this->weight,
            'event_data' => $this->event_data,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,

            'batch' => $this->whenLoaded('batch', function () {
                return [
                    'id' => $this->batch?->id,
                    'batch_code' => $this->batch?->batch_code,
                    'commodity_name' => $this->batch?->commodity_name,
                    'status' => $this->batch?->status,
                ];
            }),

            'current_owner_user' => $this->whenLoaded('currentOwner', function () {
                return [
                    'id' => $this->currentOwner?->id,
                    'name' => trim(($this->currentOwner?->fname ?? '') . ' ' . ($this->currentOwner?->lname ?? '')),
                    'email' => $this->currentOwner?->email,
                ];
            }),

            'previous_owner_user' => $this->whenLoaded('previousOwner', function () {
                return [
                    'id' => $this->previousOwner?->id,
                    'name' => trim(($this->previousOwner?->fname ?? '') . ' ' . ($this->previousOwner?->lname ?? '')),
                    'email' => $this->previousOwner?->email,
                ];
            }),
        ];
    }
}
