<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Cooperative;
use App\Models\CooperativeFarmer;
use App\Http\Resources\CooperativeFarmer as CooperativeFarmerResource;


class CooperativeController extends Controller
{
    public static function dashboard()
    {
        $user = auth()->user();
        $cooperative = $user ? Cooperative::where('user_id', $user->id)->first() : null;
        $farmers = CooperativeFarmer::where('cooperative_id', $cooperative->id)->get();


// return $farmers;

        return Inertia::render('CooperativeShow', [
            'title' => 'Cooperative',
            'response' => [
                'cooperative' => $cooperative,
                'farmers' => CooperativeFarmerResource::collection($farmers),
                'count_farmers'=>count($farmers),


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
}
