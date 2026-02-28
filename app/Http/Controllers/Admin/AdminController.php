<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\CommodityBatch;
use App\Models\Commodity;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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

        $coffeePriceOfferings = Batch::query()
            ->with('owner:id,fname,lname,email')
            ->whereIn('status', ['tokenized', 'tokenised'])
            ->latest('id')
            ->limit(30)
            ->get()
            ->map(fn (Batch $batch) => [
                'id' => $batch->id,
                'commodity_name' => $batch->commodity_name,
                'batch_code' => $batch->batch_code,
                'seller_name' => trim(($batch->owner?->fname ?? '') . ' ' . ($batch->owner?->lname ?? '')),
                'weight' => $batch->weight,
                'ask_price' => $batch->price,
                'location' => $batch->warehouse,
                'created_at' => $batch->created_at?->toDateTimeString(),
            ])
            ->values();

        return Inertia::render('AdminDashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recent_batches' => $recentBatches,
            'coffee_price_offerings' => $coffeePriceOfferings,
        ]);
    }

    /**
     * Render tokens pending admin verification.
     */
    public function unverifiedTokens(): Response
    {
        $tokens = Batch::query()
            ->with('owner:id,fname,lname,email')
            ->where('status', 'listed')
            ->latest('id')
            ->get()
            ->map(fn (Batch $batch) => [
                'id' => $batch->id,
                'token_index' => null,
                'token_hash' => null,
                'block_id' => null,
                'batch_id' => $batch->id,
                'batch_code' => $batch->batch_code,
                'commodity_name' => $batch->commodity_name,
                'weight' => $batch->weight,
                'price' => $batch->price,
                'owner_name' => trim(($batch->owner?->fname ?? '') . ' ' . ($batch->owner?->lname ?? '')),
                'owner_email' => $batch->owner?->email,
                'status' => $batch->status,
                'created_at' => $batch->created_at?->toDateTimeString(),
            ])
            ->values();

        return Inertia::render('AdminToken', [
            'title' => 'Unverified Tokens',
            'tokens' => $tokens,
        ]);
    }

    /**
     * Render admin marketplace table for all tokenized batches.
     */
    public function marketplace(): Response
    {
        $batches = Batch::query()
            ->with('owner:id,fname,lname,email')
            ->whereIn('status', ['tokenized', 'tokenised'])
            ->latest('id')
            ->get()
            ->map(fn (Batch $batch) => [
                'id' => $batch->id,
                'batch_code' => $batch->batch_code,
                'commodity_name' => $batch->commodity_name,
                'commodity_type' => $batch->commodity_type,
                'grade' => $batch->grade,
                'weight' => $batch->weight,
                'price' => $batch->price,
                'warehouse' => $batch->warehouse,
                'status' => $batch->status,
                'owner_name' => trim(($batch->owner?->fname ?? '') . ' ' . ($batch->owner?->lname ?? '')),
                'owner_email' => $batch->owner?->email,
                'created_at' => $batch->created_at?->toDateTimeString(),
            ])
            ->values();

        return Inertia::render('AdminMarketPlace', [
            'title' => 'Admin Marketplace',
            'batches' => $batches,
        ]);
    }

    /**
     * Render details page for a tokenized batch selected from admin marketplace.
     */
    public function marketplaceShow(string $id): Response
    {
        $batch = Batch::query()
            ->with([
                'owner:id,fname,lname,email',
                'activities:id,batch_id,activity,created_at',
            ])
            ->where('id', $id)
            ->whereIn('status', ['tokenized', 'tokenised'])
            ->firstOrFail();

        $batchActivities = $batch->activities
            ->sortBy('created_at')
            ->values()
            ->map(fn ($activity) => [
                'id' => $activity->id,
                'activity' => $activity->activity,
                'created_at' => $activity->created_at?->toDateTimeString(),
            ]);

        $commodityBatches = CommodityBatch::query()
            ->with([
                'commodity:id,commodity_name,commodity_type,grade,weight,status',
            ])
            ->where('batch_id', $batch->id)
            ->get();

        $batchCommodities = $commodityBatches
            ->pluck('commodity')
            ->filter()
            ->unique('id')
            ->sortBy('commodity_name')
            ->map(fn (Commodity $commodity) => [
                'id' => $commodity->id,
                'commodity_name' => $commodity->commodity_name,
                'commodity_type' => $commodity->commodity_type,
                'grade' => $commodity->grade,
                'weight' => $commodity->weight,
                'status' => $commodity->status,
            ])
            ->values();

        return Inertia::render('AdminTokenShow', [
            'title' => 'Tokenized Batch Details',
            'batch' => [
                'id' => $batch->id,
                'batch_code' => $batch->batch_code,
                'commodity_name' => $batch->commodity_name,
                'commodity_type' => $batch->commodity_type,
                'grade' => $batch->grade,
                'weight' => $batch->weight,
                'price' => $batch->price,
                'moisture' => $batch->moisture,
                'warehouse' => $batch->warehouse,
                'status' => $batch->status,
                'created_at' => $batch->created_at?->toDateTimeString(),
                'owner_name' => trim(($batch->owner?->fname ?? '') . ' ' . ($batch->owner?->lname ?? '')),
                'owner_email' => $batch->owner?->email,
            ],
            'batch_commodities' => $batchCommodities,
            'batch_activities' => $batchActivities,
        ]);
    }







    /**
     * Show the selected batch details for admin verification.
     */
    public function batchVerification(string $id): Response
    {
        $batch = Batch::query()
            ->with([
                'owner:id,fname,lname,email',
                'activities:id,batch_id,activity,created_at',
            ])
            ->findOrFail($id);

        $batchActivities = $batch->activities
            ->sortBy('created_at')
            ->values()
            ->map(fn ($activity) => [
                'id' => $activity->id,
                'activity' => $activity->activity,
                'created_at' => $activity->created_at?->toDateTimeString(),
            ]);

        // Load batch-linked commodities through the commodity_batches pivot.
        $commodityBatches = CommodityBatch::query()
            ->with([
                'commodity:id,commodity_name,commodity_type,grade,weight,status',
            ])
            ->where('batch_id', $batch->id)
            ->get();

        // Build commodity list attached to this batch from pivot-linked rows.
        $batchCommodities = $commodityBatches
            ->pluck('commodity')
            ->filter()
            ->unique('id')
            ->sortBy('commodity_name')
            ->map(fn (Commodity $commodity) => [
                'id' => $commodity->id,
                'commodity_name' => $commodity->commodity_name,
                'commodity_type' => $commodity->commodity_type,
                'grade' => $commodity->grade,
                'weight' => $commodity->weight,
                'status' => $commodity->status,
            ])
            ->values();

        return Inertia::render('AdminBatchVerification', [
            'title' => 'Admin Batch Verification',
            'batch' => [
                'id' => $batch->id,
                'batch_code' => $batch->batch_code,
                'commodity_name' => $batch->commodity_name,
                'commodity_type' => $batch->commodity_type,
                'grade' => $batch->grade,
                'weight' => $batch->weight,
                'price' => $batch->price,
                'moisture' => $batch->moisture,
                'warehouse' => $batch->warehouse,
                'status' => $batch->status,
                'created_at' => $batch->created_at?->toDateTimeString(),
                'owner_name' => trim(($batch->owner?->fname ?? '') . ' ' . ($batch->owner?->lname ?? '')),
                'owner_email' => $batch->owner?->email,
            ],
            'batch_commodities' => $batchCommodities,
            'batch_activities' => $batchActivities,
        ]);
    }





    
    /**
     * Verify a batch and transition it to tokenized status.
     */
    public function verifyBatch(string $id): RedirectResponse
    {
        $batch = Batch::query()->findOrFail($id);
        $status = strtolower((string) $batch->status);

        if (in_array($status, ['tokenized', 'tokenised'], true)) {
            return redirect()
                ->route('admin.batch.verification', ['id' => $batch->id])
                ->with('success', 'Batch is already tokenized.');
        }

        if ($status !== 'listed') {
            return redirect()
                ->route('admin.batch.verification', ['id' => $batch->id])
                ->withErrors(['batch' => 'Only listed batches can be verified and tokenized.']);
        }

        $batch->update([
            'status' => 'tokenized',
        ]);

        return redirect()
            ->route('admin.tokens.unverified')
            ->with('success', 'Batch verified and moved to tokenized status successfully.');
    }
}
