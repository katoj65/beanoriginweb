<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchChainEventResource extends JsonResource
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
            'batch_id' => $this->batch_id,
            'chain_block_id' => $this->chain_block_id,
            'event_type' => $this->event_type,
            'event_data' => $this->event_data,
            'status' => $this->status,
            'tx_hash' => $this->tx_hash,
            'occurred_at' => optional($this->occurred_at)->toDateTimeString(),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),

            'batch' => $this->whenLoaded('batch', function () {
                return new BatchResource($this->batch);
            }),

            'chain_block' => $this->whenLoaded('chainBlock', function () {
                return [
                    'id' => $this->chainBlock?->id,
                    'batch_id' => $this->chainBlock?->batch_id,
                    'block_index' => $this->chainBlock?->block_index,
                    'event_type' => $this->chainBlock?->event_type,
                    'event_data' => $this->chainBlock?->event_data,
                    'current_hash' => $this->chainBlock?->current_hash,
                    'previous_hash' => $this->chainBlock?->previous_hash,
                    'created_at' => optional($this->chainBlock?->created_at)->toDateTimeString(),
                ];
            }),
        ];
    }
}

