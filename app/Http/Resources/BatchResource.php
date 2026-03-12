<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


static function ownershipCheck(Request $request, int|string|null $ownerId): bool
{
if (!$request->user()) {
return false;
}

if((int) $ownerId === (int) $request->user()->id){
return true;
}
return false;
}







    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'batch_code' => $this->batch_code,
            'commodity_name' => $this->commodity_name,
            'commodity_type' => $this->commodity_type,
            'weight' => $this->weight,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'grade' => $this->grade,
            'moisture' => $this->moisture,
            'warehouse' => $this->warehouse,
            'is_on_chain' => (bool) $this->is_on_chain,
            'status' => $this->status,
            'ownership'=>BatchResource::ownershipCheck($request, $this->owner_id),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'owner' => $this->whenLoaded('owner', function () {
                $ownerName = trim(($this->owner?->fname ?? '') . ' ' . ($this->owner?->lname ?? ''));
                $ownerPhone = $this->owner?->userProfile?->tel;
                $ownerAddress = $this->owner?->userProfile?->address;
                $ownerEmail = $this->owner?->email;

                return [
                    'id' => $this->owner?->id,
                    'name' => $ownerName !== '' ? $ownerName : null,
                    'phone' => $ownerPhone,
                    'address' => $ownerAddress,
                    'email' => $ownerEmail,
                ];
            }),
        ];
    }





}
