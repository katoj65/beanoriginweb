<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CommodityFarm;

class BatchAttachedCommodityResource extends JsonResource
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
            'commodity_name' => $this->commodity_name,
            'commodity_type' => $this->commodity_type,
            'grade' => $this->grade,
            'weight' => $this->weight,
            'price' => $this->price,
            'ripe_percentage' => $this->ripe_percentage !== null ? (int) $this->ripe_percentage : null,
            'density_percentage' => $this->density_percentage !== null ? (int) $this->density_percentage : null,
            'harvest_date' => $this->harvest_date?->toDateString() ?? $this->harvest_date,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'farm_details' => $this->getFarm((int) $this->id),
        ];
    }

    private function getFarm(int $commodityId): ?array
    {
        // Load the linked farm first, and keep the farmer join optional so farm details still show
        // even when no cooperative farmer record is attached yet.
        $commodityFarm = CommodityFarm::query()
            ->join('farms', 'commodity_farms.farm_id', '=', 'farms.id')
            ->leftJoin('farmers', 'farms.cooperative_farmer_id', '=', 'farmers.id')
            ->where('commodity_farms.commodity_id', $commodityId)
            ->select([
                'commodity_farms.commodity_id as commodity_id',
                'commodity_farms.farm_id as farm_id',
                'farms.farm_name as farm_name',
                'farms.location as location',
                'farms.area_acres as area_acres',
                'farms.cooperative_farmer_id as cooperative_farmer_id',
                'farmers.first_name as first_name',
                'farmers.last_name as last_name',
                'farmers.phone_number as phone_number',
            ])
            ->first();

        if ($commodityFarm === null) {
            return null;
        }

        return [
            'commodity_id' => $commodityFarm->commodity_id,
            'farm_id' => $commodityFarm->farm_id,
            'farm_name' => $commodityFarm->farm_name,
            'location' => $commodityFarm->location,
            'area_acres' => $commodityFarm->area_acres,
            'cooperative_farmer_id' => $commodityFarm->cooperative_farmer_id,
            'first_name' => $commodityFarm->first_name,
            'last_name' => $commodityFarm->last_name,
            'phone_number' => $commodityFarm->phone_number,
        ];
    }






}
