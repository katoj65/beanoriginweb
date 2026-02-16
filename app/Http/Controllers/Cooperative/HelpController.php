<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\Cooperative;
use App\Models\HelpCenter;
use Illuminate\Http\Request;
use Inertia\Inertia;


class HelpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('CooperativeHelp',
        [
            'title' => 'Help Center',
            'response' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'in:technical,payments,farmers,compliance,partnership,other'],
            'priority' => ['required', 'in:low,normal,high,critical'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'preferred_channel' => ['required', 'in:email,phone,inapp'],
            'contact_name' => ['required','string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'regex:/^[0-9+()\\-\\s]{7,20}$/'],
            'status' => ['nullable', 'in:open,in_progress,resolved,closed'],
            'cooperative_id' => ['nullable', 'exists:cooperative,id'],
        ]);

        $cooperativeId = $validated['cooperative_id']
            ?? Cooperative::where('user_id', $request->user()->id)->value('id');

        HelpCenter::create([
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'preferred_channel' => $validated['preferred_channel'],
            'contact_name' => $validated['contact_name'] ?? null,
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'],
            'status' => $validated['status'] ?? 'open',
            'user_id' => $request->user()->id,
            'cooperative_id' => $cooperativeId,
        ]);

        return back()->with('success', 'Help request submitted successfully.');
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
