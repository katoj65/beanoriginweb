<?php

namespace App\Services\Buy;

use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BuyService
{

public function ReserveBatch(Batch $batch, Request $request): JsonResponse
{
$batch->status='request';
$batch->save();
return response()->json($batch);
}


















}
