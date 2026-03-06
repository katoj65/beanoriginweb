<?php

namespace App\Http\Controllers\Market;

use App\Http\Controllers\Controller;
use App\Models\BatchPurchaseRequest;
use Inertia\Inertia;
use Inertia\Response;

class BiddingController extends Controller
{
    public function index(): Response
    {
        $userId = (int) auth()->id();

        $requests = BatchPurchaseRequest::query()
            ->with([
                'user:id,fname,lname,email',
                'batch:id,owner_id,batch_code,commodity_name,commodity_type,grade,weight,quantity,price,warehouse,status',
            ])
            ->whereHas('batch', fn ($query) => $query->where('owner_id', $userId))
            ->latest('id')
            ->get()
            ->map(function (BatchPurchaseRequest $request) {
                $buyer = trim(implode(' ', array_filter([
                    $request->user?->fname,
                    $request->user?->lname,
                ])));

                return [
                    'id' => $request->id,
                    'batch_id' => $request->batch_id,
                    'batch_code' => $request->batch?->batch_code ?? ('#'.$request->batch_id),
                    'commodity_name' => $request->batch?->commodity_name ?? 'N/A',
                    'commodity_type' => $request->batch?->commodity_type ?? 'N/A',
                    'grade' => $request->batch?->grade ?? 'N/A',
                    'weight' => $request->batch?->weight,
                    'quantity' => $request->batch?->quantity,
                    'ask_price' => $request->batch?->price,
                    'warehouse' => $request->batch?->warehouse ?? 'N/A',
                    'status' => $request->batch?->status ?? 'pending',
                    'activity' => $request->activity ?? 'request',
                    'buyer_name' => $buyer !== '' ? $buyer : 'N/A',
                    'buyer_email' => $request->user?->email ?? 'N/A',
                    'requested_at' => $request->created_at?->toDateTimeString(),
                ];
            })
            ->values();

        return Inertia::render('BiddingPage', [
            'response' => [
                'requests' => $requests,
                'total' => $requests->count(),
            ],
        ]);
    }
}
