<?php

namespace App\Http\Controllers\Produce;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProduceResource;
use App\Models\Produce;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProduceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produces = Produce::query()
            ->latest('id')
            ->get();

        return Inertia::render('ProducePage', [
            'title' => 'Coffee Harvest Batches',
            'produces' => ProduceResource::collection($produces),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => ['required', 'exists:farms,id'],
            'user_id' => ['required', 'exists:users,id'],
            'crop_name' => ['required', 'string', 'max:255'],
            'crop_type' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'location' => ['required', 'string', 'max:255'],
            'date_of_havest' => ['required', 'date'],
            'crop_grade' => ['required', 'string', 'max:255'],
            'process_method' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        Produce::create($validated);

        return redirect()->back()->with('success', 'Produce added successfully.');
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
