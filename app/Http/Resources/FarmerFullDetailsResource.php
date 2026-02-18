<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmerFullDetailsResource extends JsonResource
{
/**
 * Transform the resource into an array.
 *
 * @return array<string, mixed>
 */
public function toArray(Request $request): array
{
return [
'first_name'=>$this->first_name,
'last_name'=>$this->last_name,
'gender'=>$this->gender,
'dob'=>$this->date_of_birth,
'location'=>$this->district.', '.$this->sub_county.', '.$this->village,
'crop'=>$this->primary_crop,
'telephone'=>$this->phone_number,
'email'=>$this->email,
'status'=>$this->status,
'created_at'=>$this->created_at?->format('Y-m-d')


];
}
}
