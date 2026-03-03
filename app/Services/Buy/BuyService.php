<?php

namespace App\Services\Buy;

use App\Models\Batch;
use Illuminate\Http\Request;
use App\Models\BatchActivityLog;
use App\Models\BatchPurchaseRequest;
use Illuminate\Http\JsonResponse;

class BuyService
{

public function ReserveBatch(Batch $batch, Request $request): JsonResponse
{
$userId=$request->user()->id;
$batchId = (int) $batch->getRawOriginal('id');
BatchPurchaseRequest::create(['user_id'=>$userId,'batch_id'=>$batchId,'activity'=>'request']);
$batch->status='request';
$batch->save();
return response()->json($batch);
}


















}
