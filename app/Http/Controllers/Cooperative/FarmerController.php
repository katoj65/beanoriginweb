<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Http\Resources\CooperativeFarmer as CooperativeFarmerResource;
use App\Http\Resources\FarmResource;
use App\Models\Cooperative;
use App\Models\CooperativeFarmer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Farm;

class FarmerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farmers = CooperativeFarmer::query()
            ->where('cooperative_id', $cooperativeId)
            ->latest('id')
            ->get();

        return Inertia::render('CooperativeFarmer', [
            'title' => 'Cooperative Farmers',
            'farmers' => CooperativeFarmerResource::collection($farmers),
            'stats' => [
                'total' => $farmers->count(),
                'active' => $farmers->where('status', 'active')->count(),
                'pending' => $farmers->where('status', 'pending')->count(),
            ],
        ]);
    }

    /**
     * Show form to create a new farmer.
     */
    public function create()
    {
        return Inertia::render('CooperativeFarmerCreate', [
            'title' => 'Add Farmer',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\\-\\s]{7,20}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'district' => ['required', 'string', 'max:255'],
            'sub_county' => ['required', 'string', 'max:255'],
            'village' => ['required', 'string', 'max:255'],
            'primary_crop' => ['nullable', 'string', 'max:255'],
       
        ]);

        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');
        $farmer = CooperativeFarmer::create([
            ...$validated,
            'cooperative_id' => $cooperativeId,
            'status' => $validated['status'] ?? 'pending',
        ]);
        return redirect()
            ->route('cooperative.farmers.show', $farmer->id)
            ->with('success', 'Farmer added successfully.');
    }






    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

        $farmer = CooperativeFarmer::query()
            ->where('cooperative_id', $cooperativeId)
            ->findOrFail($id);

        $farms = Farm::query()
            ->where('cooperative_farmer_id', $farmer->id)
            ->get();

        return Inertia::render('CooperativeFarmerShow', [
            'title' => 'Farmer Details',
            'farmer' => new CooperativeFarmerResource($farmer),
            'farms' => FarmResource::collection($farms),
        ]);
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
