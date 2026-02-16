<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Http\Resources\FarmResource;
use App\Models\Cooperative;
use App\Models\CooperativeFarmer;
use App\Models\Farm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create()
    {
        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farmers = CooperativeFarmer::query()
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
            'cooperative_farmer_id' => ['required', 'exists:cooperative_farmers,id'],
            'farm_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'area_acres' => ['required', 'numeric', 'min:0'],
            'primary_crop' => ['required', 'string', 'max:255'],
            'soil_type' => ['required', 'string', 'max:255'],
            'water_source_type' => ['required', 'string', 'max:255'],


        ]);

        $farm = Farm::create($validated);

        return redirect()->back()->with([
            'success' => 'Farm fields validated successfully.',
            'farm' => (new FarmResource($farm))->resolve(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
