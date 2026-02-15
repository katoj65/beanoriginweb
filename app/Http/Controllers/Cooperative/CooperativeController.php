<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Cooperative;

class CooperativeController extends Controller
{
    public static function dashboard()
    {
        $user = auth()->user();
        $cooperative = $user ? Cooperative::where('user_id', $user->id)->first() : null;

        return Inertia::render('CooperativeShow', [
            'title' => 'Cooperative',
            'response' => [
                'cooperative' => $cooperative,
                'cooperative_exists' => (bool) $cooperative,
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


        return Inertia::render('CooperativeShow', [
            'title' => 'cooperative',
            'response' => [
         
            ],
        ]);
    }
}
