<?php

namespace App\Services\Buy;

use App\Models\Batch;
use Illuminate\Http\Request;
use App\Models\BatchActivityLog;
use Illuminate\Http\JsonResponse;

class BuyService
{

public function ReserveBatch(Batch $batch, Request $request): JsonResponse
{
$userId=$request->user()->id;
$log=BatchActivityLog::create(['user_id'=>$userId,'batch_id'=>$batch->id,'activity'=>'reserved']);
$batch->status='reserved';
$batch->save();
return response()->json($batch);
}


















}
