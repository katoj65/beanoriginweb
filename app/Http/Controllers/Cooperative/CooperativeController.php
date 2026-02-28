<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Cooperative;
use App\Models\CooperativeFarmer;
use App\Models\Produce;
use App\Models\Batch;
use App\Http\Resources\CooperativeFarmer as CooperativeFarmerResource;
use App\Http\Resources\FarmersTableSummaryResource;
use App\Http\Resources\ProduceResource;


class CooperativeController extends Controller
{

public static function dashboard()
{

$user = auth()->user();
$cooperative = Cooperative::where('user_id', $user->id)->first();
if (!$cooperative) {
return Inertia::render('CooperativeCreate', [
'title' => 'Create Cooperative',
'response' => [],
]);
}

$cooperative = $user ? Cooperative::where('user_id', $user->id)->first() : null;
$farmers = CooperativeFarmer::where('cooperative_id', $cooperative->id)->get();
$produces = Batch::query()
->where('owner_id', $user->id)
->whereIn('status', ['tokenized', 'tokenized'])
->get();
$totalQuantity = Produce::where('cooperative_id', $cooperative->id)->sum('quantity');
$soldCount = $user ? Batch::where('owner_id', $user->id)->where('status', 'sold')->count() : 0;
$listedBatchWeightTotal = $user ? Batch::where('owner_id', $user->id)->where('status', 'tokenized')->sum('weight') : 0;

return Inertia::render('CooperativeShow', [
'title' => 'Cooperative',
'response' => [
'cooperative' => $cooperative,
'farmers' => FarmersTableSummaryResource::collection($farmers)||[],
'count_farmers'=>count($farmers),
'total_quantity' => $totalQuantity,
'listed_quantity_total' =>  $listedBatchWeightTotal,
'sold_count' => $soldCount,
'produces' => $produces
->map(fn (Batch $batch) => [
'id' => $batch->id,
'batch_id' => $batch->id,
'commodity_name' => $batch->commodity_name,
'batch_code' => $batch->batch_code,
'weight' => $batch->weight,
'price' => $batch->price,
'status' => $batch->status,
'created_at' => $batch->created_at?->toDateTimeString(),
])
->values(),

],
]);

}








public function create_cooperative(Request $request)
{
return Inertia::render('CooperativeCreate', [
'title' => 'create cooperative',
'response' => [],
]);
}





public function store(Request $request)
{
$validated = $request->validate([
'legal_name' => ['required', 'string', 'max:255'],
'name' => ['required', 'string', 'max:255'],
'reg_num' => ['required', 'string', 'max:100'],
'reg_date' => ['required', 'date', 'before_or_equal:today'],
'district' => ['required', 'string', 'max:255'],
'physical_address' => ['required', 'string', 'max:500'],
'postal_address' => ['required', 'string', 'max:500'],
'email' => ['required', 'email', 'max:255'],
'telephone' => ['required', 'string', 'regex:/^[0-9+()\\-\\s]{7,20}$/'],
'website' => ['nullable', 'url', 'max:255'],
]);

$model = Cooperative::create([
'legal_name' => $validated['legal_name'],
'name' => $validated['name'],
'reg_num' => $validated['reg_num'],
'reg_date' => $validated['reg_date'],
'district' => $validated['district'],
'physical_address' => $validated['physical_address'],
'postal_address' => $validated['postal_address'],
'email' => $validated['email'],
'tel' => $validated['telephone'],
'website' => $validated['website'] ?? '',
'user_id' => $request->user()->id,
]);

return redirect('/cooperative/' . $model->id)->with('success', 'Cooperative account created successfully.');
}



public function show(Request $request)
{
return self::dashboard();
}



public function produce()
{
return Inertia::render('ProducePage', [
'title' => 'Coffee Harvest Batches',
'response' => [],
]);
}
}
