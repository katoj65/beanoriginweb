<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'commodity' => $this->commodity,
            'type' => $this->type,
            'grade' => $this->grade,
            'min_price' => $this->min_price !== null ? (float) $this->min_price : null,
            'max_price' => $this->max_price !== null ? (float) $this->max_price : null,
            'quantity' => (float) $this->quantity,
        ];
    }
}
