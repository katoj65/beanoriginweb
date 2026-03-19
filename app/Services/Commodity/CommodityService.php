<?php

namespace App\Services\Commodity;

use App\Http\Resources\CommodityResource;
use App\Models\Commodity;
use App\Models\CommodityQualityData;
use App\Models\Cooperative;
use App\Models\QualityMetadata;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CommodityService
{
// Build the full payload for the commodity details page.
public function show(Request $request, string $id): Response
{

$commodity = Commodity::with('farms:id,cooperative_farmer_id,farm_name,location,area_acres')
->where('id', $id)
->firstOrFail();


return Inertia::render('CommodityShow', [
'commodity' => new CommodityResource($commodity),
'origin_farms' => $commodity->farms
->map(fn ($farm) => [
'id' => $farm->id,
'cooperative_farmer_id' => $farm->cooperative_farmer_id,
'farm_name' => $farm->farm_name,
'location' => $farm->location,
'latitude' => $farm->latitude,
'longitude' => $farm->longitude,
'area_acres' => $farm->area_acres,
])
->values(),

// Provide selectable quality activity metadata for the quality form.
'quality_metadata' => QualityMetadata::query()
->orderBy('activity')
->pluck('activity')
->values(),

// Provide saved commodity quality rows for on-page quality table.
'commodity_quality_data' => CommodityQualityData::query()
->where('commodity_id', (int) $id)
->latest('id')
->get(['id', 'activity', 'value', 'created_at'])
->map(fn ($item) => [
'id' => $item->id,
'activity' => $item->activity,
'value' => $item->value,
'created_at' => $item->created_at?->toDateTimeString(),
])
->values(),
'can' => [
'view' => $request->user()?->can('view', $commodity) ?? false,
'update' => $request->user()?->can('update', $commodity) ?? false,
'delete' => $request->user()?->can('delete', $commodity) ?? false,
'create' => $request->user()?->can('create', Commodity::class) ?? false,
'view_any' => $request->user()?->can('viewAny', Commodity::class) ?? false,
'is_owner' => $request->user()?->can('isOwner', $commodity) ?? false,
]
]);
}
}
