<?php

namespace App\Http\Controllers\Produce;

use App\Http\Controllers\Controller;
use App\Models\Cooperative;
use App\Models\Farm;
use App\Models\Produce;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Crops;
use App\Http\Resources\CropResource;

class ProduceController extends Controller
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
->orderBy('farm_name')
->get(['id', 'farm_name']);


return Inertia::render('ProducePage', [
'title' => 'Add Produce',
'farms' => $farms,

]);
}

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
$validated = $request->validate([
'farm_id' => ['required', 'exists:farms,id'],
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

Produce::create([
...$validated,
'user_id' => auth()->id(),
]);

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


public function create(Request $request)
{
$crops=Crops::get();

return Inertia::render('ProduceCreate', [
'title' => 'Add Produce',
'crops'=>CropResource::collection($crops),


]);
}











}
