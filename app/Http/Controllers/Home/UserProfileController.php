<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfile;

class UserProfileController extends Controller
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gender' => ['required', 'in:male,female,other'],
            'dob' => ['required', 'date', 'before_or_equal:today'],
            'address' => ['required', 'string', 'max:255'],
            'tel' => ['required', 'string', 'regex:/^[0-9+()\\-\\s]{7,20}$/'],
        ]);

        UserProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'gender' => $validated['gender'],
                'dob' => $validated['dob'],
                'address' => $validated['address'],
                'tel' => $validated['tel'],
            ]
        );

        return back()->with('success', 'Profile saved successfully.');

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



public function update_user_account_status(Request $request)
{
$validated = $request->validate([
'item' => ['required', 'in:Farmer,Cooperative,Investor,Financier,Exporter'],
]);
$roleMap = [
'Farmer' => 'farmer',
'Cooperative' => 'cooperative',
'Investor' => 'investor',
'Financier' => 'financier',
'Exporter' => 'exporter',
];

$request->user()->update([
'role' => $roleMap[$validated['item']],
]);

return redirect()->route('cooperative_create')->with('success', 'Account role updated successfully.');
}





















}
