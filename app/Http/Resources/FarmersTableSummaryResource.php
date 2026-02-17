<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmersTableSummaryResource extends JsonResource
{
/**
 * Transform the resource into an array.
 *
 * @return array<string, mixed>
 */
public function toArray(Request $request): array
{
return [
'id'=>$this->id,
'name'=>$this->first_name.' '.$this->last_name,
'gender'=>$this->gender,
'produce'=>$this->primary_crop,
'location'=>$this->district,
'status'=>$this->status
];
}
}
