<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Http\Resources\CooperativeFarmer as CooperativeFarmerResource;
use App\Http\Resources\FarmResource;
use App\Models\Cooperative;
use App\Models\Farmer;
use App\Models\Farm;
use App\Models\FarmSustainabilityData;
use App\Models\SustainabilityMetadata;
use App\Models\User;
use App\Services\Map\MapService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farms = Farm::query()
            ->whereHas('farmer', function ($query) use ($cooperativeId) {
                $query->where('cooperative_id', $cooperativeId);
            })
            ->with('farmer')
            ->latest('id')
            ->get();

        return Inertia::render('FarmPage', [
            'title' => 'Farms',
            'farms' => $farms->map(fn ($farm) => [
                'id' => $farm->id,
                'farm_name' => $farm->farm_name,
                'location' => $farm->location,
                'area_acres' => $farm->area_acres,
                'latitude' => $farm->latitude,
                'longitude' => $farm->longitude,
                'created_at' => optional($farm->created_at)->format('Y-m-d H:i:s'),
                'farmer_name' => trim(($farm->farmer?->first_name ?? '') . ' ' . ($farm->farmer?->last_name ?? '')) ?: 'N/A',
            ])->values(),
            'can' => [
                'access_platform' => request()->user()?->can('accessPlatform', User::class) ?? false,
                'is_cooperative' => request()->user()?->can('isCooperative', User::class) ?? false,
                'is_admin' => request()->user()?->can('isAdmin', User::class) ?? false,
                'is_buyer' => request()->user()?->can('isBuyer', User::class) ?? false,
                'is_investor' => request()->user()?->can('isInvestor', User::class) ?? false,
                'is_organisation' => request()->user()?->can('isOrganisation', User::class) ?? false,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create()
    {
        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farmers = Farmer::query()
            ->where('cooperative_id', $cooperativeId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name']);

        return Inertia::render('FarmersFarmCreate', [
            'title' => 'Add Farm',
            'farmers' => $farmers->map(fn ($farmer) => [
                'id' => $farmer->id,
                'name' => trim($farmer->first_name . ' ' . $farmer->last_name),
            ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cooperative_farmer_id' => ['required', 'exists:farmers,id'],
            'farm_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'area_acres' => ['required', 'numeric', 'min:0'],
        ]);

        Farm::create($validated);

        return redirect()->back()->with([
            'success' => 'Farm has been saved successfully',
        ]);
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id, MapService $mapService)
    {
        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farm = Farm::query()
            ->whereHas('farmer', function ($query) use ($cooperativeId) {
                $query->where('cooperative_id', $cooperativeId);
            })
            ->with('farmer')
            ->findOrFail($id);

        // Resolve the current map payload for the saved farm coordinates.
        $mapData = ($farm->latitude !== null && $farm->longitude !== null)
            ? $mapService->map($farm->latitude, $farm->longitude)
            : null;

        return Inertia::render('FarmShow', [
            'title' => 'Farm Details',
            'farm' => new FarmResource($farm),
            'owner' => new CooperativeFarmerResource($farm->farmer),
            'map_data' => $mapData,
            'sustainability_metadata' => SustainabilityMetadata::query()
                ->orderBy('activity')
                ->pluck('activity')
                ->values(),
            'farm_sustainability_data' => FarmSustainabilityData::query()
                ->where('farm_id', $farm->id)
                ->latest('id')
                ->get()
                ->map(fn ($item) => [
                    'id' => $item->id,
                    'activity' => $item->activity,
                    'value' => $item->value,
                    'created_at' => optional($item->created_at)->format('Y-m-d H:i:s'),
                ])
                ->values(),
            'can' => [
                'is_owner' => request()->user()?->can('isOwner', $farm) ?? false,
            ],
        ]);
    }






    public function showCooperative(string $id, MapService $mapService)
    {
        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farm = Farm::query()
            ->whereHas('farmer', function ($query) use ($cooperativeId) {
                $query->where('cooperative_id', $cooperativeId);
            })
            ->with('farmer')
            ->findOrFail($id);

        // Build the cooperative-facing map payload for the farm profile view.
        $mapData = ($farm->latitude !== null && $farm->longitude !== null)
            ? $mapService->map($farm->latitude, $farm->longitude)
            : null;

        // Reuse the same farm details page for the cooperative-specific route entry point.
        return Inertia::render('FarmShowCooperative', [
            'title' => 'Farm Details',
            'farm' => new FarmResource($farm),
            'owner' => new CooperativeFarmerResource($farm->farmer),
            'map_data' => $mapData,
            'sustainability_metadata' => SustainabilityMetadata::query()
                ->orderBy('activity')
                ->pluck('activity')
                ->values(),
            'farm_sustainability_data' => FarmSustainabilityData::query()
                ->where('farm_id', $farm->id)
                ->latest('id')
                ->get()
                ->map(fn ($item) => [
                    'id' => $item->id,
                    'activity' => $item->activity,
                    'value' => $item->value,
                    'created_at' => optional($item->created_at)->format('Y-m-d H:i:s'),
                ])
                ->values(),
        ]);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'farm_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'area_acres' => ['required', 'numeric', 'min:0'],
        ]);

        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farm = Farm::query()
            ->whereHas('farmer', function ($query) use ($cooperativeId) {
                $query->where('cooperative_id', $cooperativeId);
            })
            ->findOrFail($id);

        $farm->update($validated);

        return redirect()->route('cooperative.farms.show', ['id' => $farm->id])->with([
            'success' => 'Farm details updated successfully.',
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farm = Farm::query()
            ->whereHas('farmer', function ($query) use ($cooperativeId) {
                $query->where('cooperative_id', $cooperativeId);
            })
            ->findOrFail($id);

        $farm->delete();

        return redirect()->back()->with([
            'success' => 'Farm deleted successfully.',
        ]);
    }






    public function farmUpdatePage(Request $request, string $id)
    {
        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farm = Farm::query()
            ->whereHas('farmer', function ($query) use ($cooperativeId) {
                $query->where('cooperative_id', $cooperativeId);
            })
            ->findOrFail($id);

        return Inertia::render('FarmUpdatePage', [
            'title' => 'Update Farm',
            'farm' => new FarmResource($farm),
        ]);
    }





    public function storeFarmSustainabilityData(Request $request, string $id)
    {
        $validated = $request->validate([
            'activity' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farm = Farm::query()
            ->whereHas('farmer', function ($query) use ($cooperativeId) {
                $query->where('cooperative_id', $cooperativeId);
            })
            ->findOrFail($id);

        $activityExists = FarmSustainabilityData::query()
            ->where('farm_id', $farm->id)
            ->where('activity', trim($validated['activity']))
            ->exists();
        if ($activityExists) {
            throw ValidationException::withMessages([
                'activity' => 'Activity has already been added.',
            ]);
        }

        FarmSustainabilityData::create([
            'farm_id' => $farm->id,
            'activity' => trim($validated['activity']),
            'value' => $validated['value'],
        ]);

        return redirect()
            ->route('cooperative.farms.show', ['id' => $farm->id])
            ->with('success', 'Farm sustainability data saved successfully.');
    }




public function destroySustainabilityData(Request $request, string $id, string $sustainabilityId){
    $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

    $farm = Farm::query()
        ->whereHas('farmer', function ($query) use ($cooperativeId) {
            $query->where('cooperative_id', $cooperativeId);
        })
        ->findOrFail($id);

    $model = FarmSustainabilityData::query()
        ->where('farm_id', $farm->id)
        ->findOrFail($sustainabilityId);
    $model->delete();

    return redirect()
        ->route('cooperative.farms.show', ['id' => $farm->id])
        ->with('success', 'Farm sustainability data deleted successfully.');
}









   public function updateLocation(Request $request, string $id)
    {
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farm = Farm::query()
            ->whereHas('farmer', function ($query) use ($cooperativeId) {
                $query->where('cooperative_id', $cooperativeId);
            })
            ->findOrFail($id);

        $farm->update($validated);

        return redirect()->route('cooperative.farms.show', ['id' => $farm->id])->with([
            'success' => 'Farm coordinates updated successfully.',
        ]);
    }













}
