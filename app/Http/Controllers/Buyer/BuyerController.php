<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlockBatchLatestResource;
use App\Models\Block;
use App\Models\BlockPurchase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class BuyerController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public static function dashboard()
    {
        return Inertia::render('BuyerDashboard', [
            'title' => 'buyer dashboard',
        ]);
    }

    public function market()
    {
        $listings = $this->marketListingsBaseQuery()->get();

        return Inertia::render('MarketPage', [
            'title' => 'market',
            'response' => [
                'listings' => BlockBatchLatestResource::collection($listings),
                'listed_count' => $listings->count(),
                'total_weight' => $listings->sum('weight'),
                'average_price' => $listings->count() > 0
                    ? round((float) $listings->avg('price'), 2)
                    : 0,
            ],
        ]);
    }

    public function marketShow(int $id)
    {
        $listing = $this->marketListingsBaseQuery()
            ->where('batches.id', $id)
            ->firstOrFail();

        $listingPayload = BlockBatchLatestResource::make($listing)->resolve();
        $eventData = is_array($listingPayload['event_data'] ?? null) ? $listingPayload['event_data'] : [];

        $listingPayload['commodity_type'] = $eventData['commodity_type'] ?? null;
        $listingPayload['warehouse'] = $eventData['warehouse'] ?? null;
        $listingPayload['moisture'] = $eventData['moisture'] ?? null;
        $listingPayload['description'] = $eventData['description'] ?? null;
        $listingPayload['comments'] = $eventData['comments'] ?? null;

        $historyColumns = ['block_index', 'event_type', 'price', 'weight', 'created_at'];
        if (Schema::hasColumn('blocks', 'status')) {
            $historyColumns[] = 'status';
        }

        $history = Block::query()
            ->where('batch_id', $id)
            ->select($historyColumns)
            ->orderByDesc('block_index')
            ->limit(10)
            ->get()
            ->map(function (Block $block) {
                return [
                    'block_index' => $block->block_index,
                    'event_type' => $block->event_type,
                    'status' => $block->status ?? 'active',
                    'price' => $block->price,
                    'weight' => $block->weight,
                    'created_at' => $block->created_at?->toDateTimeString(),
                ];
            })
            ->values();

        return Inertia::render('MarketShowPage', [
            'title' => 'market details',
            'response' => [
                'listing' => $listingPayload,
                'history' => $history,
            ],
        ]);
    }

    public function buyBlockPage(int $id)
    {
        $listing = $this->marketListingsBaseQuery()
            ->where('batches.id', $id)
            ->firstOrFail();

        $listingPayload = BlockBatchLatestResource::make($listing)->resolve();
        $eventData = is_array($listingPayload['event_data'] ?? null) ? $listingPayload['event_data'] : [];

        $listingPayload['block_id'] = $listing->block_id ?? null;
        $listingPayload['seller_id'] = $listing->seller_id ?? null;
        $listingPayload['commodity_type'] = $eventData['commodity_type'] ?? null;
        $listingPayload['warehouse'] = $eventData['warehouse'] ?? null;
        $listingPayload['moisture'] = $eventData['moisture'] ?? null;
        $listingPayload['description'] = $eventData['description'] ?? null;

        $recentBids = BlockPurchase::query()
            ->with('buyer:id,fname,lname')
            ->where('block_id', $listing->block_id)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function (BlockPurchase $bid) {
                $buyer = trim(implode(' ', array_filter([$bid->buyer?->fname, $bid->buyer?->lname])));

                return [
                    'id' => $bid->id,
                    'buyer_name' => $buyer !== '' ? $buyer : 'Buyer',
                    'purchase_price' => $bid->purchase_price,
                    'currency' => $bid->currency,
                    'status' => $bid->status,
                    'payment_method' => $bid->payment_method,
                    'created_at' => $bid->created_at?->toDateTimeString(),
                ];
            })
            ->values();

        return Inertia::render('BuyBlockPage', [
            'title' => 'buy block',
            'response' => [
                'listing' => $listingPayload,
                'recent_bids' => $recentBids,
            ],
        ]);
    }

    public function storeBid(Request $request, int $id)
    {
        $payload = $request->validate([
            'bid_price' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:10'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $listing = $this->marketListingsBaseQuery()
            ->where('batches.id', $id)
            ->firstOrFail();

        $buyerId = (int) $request->user()->id;
        $sellerId = (int) ($listing->seller_id ?? 0);
        $blockId = (int) ($listing->block_id ?? 0);

        if ($sellerId <= 0 || $blockId <= 0) {
            return back()->withErrors([
                'bid_price' => 'This listing is not available for bidding right now.',
            ]);
        }

        if ($buyerId === $sellerId) {
            return back()->withErrors([
                'bid_price' => 'You cannot bid on your own listing.',
            ]);
        }

        BlockPurchase::query()->create([
            'block_id' => $blockId,
            'purchase_price' => $payload['bid_price'],
            'currency' => $payload['currency'],
            'buyer_id' => $buyerId,
            'seller_id' => $sellerId,
            'payment_method' => $payload['payment_method'] ?? null,
            'status' => 'pending',
            'notes' => $payload['message'] ?? null,
        ]);

        return redirect()
            ->route('buyer.market.buy', ['id' => $id])
            ->with('success', 'Bid submitted successfully.');
    }

    private function marketListingsBaseQuery(): Builder
    {
        $query = Block::query()
            ->select(
                'blocks.id as block_id',
                'blocks.current_owner as seller_id',
                'batches.id as batch_id',
                'batches.commodity_name',
                'batches.batch_code',
                'blocks.created_at',
                'blocks.price',
                'blocks.weight',
                'blocks.event_type',
                'blocks.event_data'
            )
            ->join('batches', 'blocks.batch_id', '=', 'batches.id')
            ->where('blocks.event_type', 'listed');

        if (Schema::hasColumn('blocks', 'status')) {
            $query->addSelect('blocks.status');
            $query->where('blocks.status', 'active');
        }

        $query->whereIn('blocks.block_index', function ($subQuery) {
            $subQuery->from('blocks')
                ->selectRaw('MAX(block_index)')
                ->where('event_type', 'listed');

            if (Schema::hasColumn('blocks', 'status')) {
                $subQuery->where('status', 'active');
            }

            $subQuery->groupBy('batch_id');
        });

        return $query;
    }
}
