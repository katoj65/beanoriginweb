<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
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
            'token_index' => $this->token_index,
            'event_type' => $this->event_type,
            'metadata' => $this->metadata,
            'current_hash' => $this->current_hash,
            'previous_hash' => $this->previous_hash,
            'current_owner' => $this->current_owner,
            'previous_owner' => $this->previous_owner,
            'status' => $this->status,
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
        ];
    }
}

