<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\BatchBid;

class BidMetadataResource extends JsonResource
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
 'user_id' => $this->user_id,
 'bid_quantity' => $this->bid_quantity,
 'bid_price' => $this->bid_price,
 'bid_notes' => $this->bid_notes,
 'status' => $this->status,
 'number_of_bidders'=>BatchBid::where('batch_id',$this->id)->count(),
 'created_at' => optional($this->created_at)->toDateTimeString(),
 'updated_at' => optional($this->updated_at)->toDateTimeString(),
];
}
}
