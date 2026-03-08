<?php

namespace App\Http\Controllers\Market;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Inertia\Inertia;
use Inertia\Response;

class BiddingController extends Controller
{
public function index(): Response
{
$batches = Batch::query()
->where('market_type','bidding')
->select([
'id',
'batch_code',
'commodity_name',
'commodity_type',
'grade',
'weight',
'quantity',
'price',
'warehouse',
'status',
'market_type',
])
->latest('id')
->get();

return Inertia::render('BiddingPage', [
'batches' => $batches,
]);
}
}
