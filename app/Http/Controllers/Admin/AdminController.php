<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Commodity;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
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

    /**
     * Render the admin dashboard with platform summary metrics.
     */
    static function dashboard(): Response
    {
        $stats = [
            'total_users' => User::query()->count(),
            'cooperatives' => User::query()->where('role', 'cooperative')->count(),
            'buyers' => User::query()->where('role', 'buyer')->count(),
            'commodities' => Commodity::query()->count(),
            'batches' => Batch::query()->count(),
            'tokenized_batches' => Batch::query()->whereIn('status', ['tokenized', 'tokenised'])->count(),
            'tokens' => Token::query()->count(),
        ];

        $recentBatches = Batch::query()
            ->latest('id')
            ->limit(8)
            ->get(['id', 'batch_code', 'commodity_name', 'status', 'created_at'])
            ->map(fn (Batch $batch) => [
                'id' => $batch->id,
                'batch_code' => $batch->batch_code,
                'commodity_name' => $batch->commodity_name,
                'status' => $batch->status,
                'created_at' => $batch->created_at?->toDateTimeString(),
            ])
            ->values();

        return Inertia::render('AdminDashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recent_batches' => $recentBatches,
        ]);
    }







}
