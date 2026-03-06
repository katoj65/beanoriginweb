<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Batch;

class MarketBoardResource extends JsonResource
{
/**
 * Transform the resource into an array.
 *
 * @return array<string, mixed>
 */
public function toArray(Request $request): array
{
return [
'name'=>$this->name,
'value'=>[
'available'=> (int) Batch::where('status','!=','bought')->where('commodity_type',$this->name)->sum('weight'),
'sold'=> (int) Batch::where('status','bought')->where('commodity_type',$this->name)->sum('weight')
]

];
}
}
