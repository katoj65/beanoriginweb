<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\Cooperative;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountSettings extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $cooperative = $user ? Cooperative::where('user_id', $user->id)->first() : null;

        return Inertia::render('CooperativeAccountSettings', [
            'title' => 'account settings',
            'response' => [
                'cooperative' => $cooperative,
                'cooperative_exists' => (bool) $cooperative,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
