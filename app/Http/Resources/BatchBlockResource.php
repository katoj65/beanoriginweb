<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Block;

class BatchBlockResource extends JsonResource
{
/**
 * Transform the resource into an array.
 *
 * @return array<string, mixed>
 */
public function toArray(Request $request): array
{

$block=new BlockResource(Block::query()
->where('batch_id', $this->id)
->where('current_owner', $this->owner_id)
->where('event_type', 'listed')
->latest()->first());



return [
'id' => $this->id,
'commodity_name' => $this->commodity_name,
'commodity_type' => $this->commodity_type,
'weight' => $block->weight ?? $this->weight,
'grade' => $this->grade,
'price' => $block->price ?? $this->price,
'moisture' => $this->moisture,
'warehouse' => $this->warehouse,
'status' => $this->status,
'created_at' => $block->created_at?->toDateTimeString(),


];
}
}
