<?php

use Illuminate\Support\Facades\Route;

// Commodity-prefixed routes are loaded from routes/web.php under auth middleware.
use App\Http\Controllers\Commodity\CommodityController;
use App\Http\Controllers\Batch\BatchController;

Route::get('/create', [CommodityController::class, 'create'])->name('create');
Route::post('/store', [CommodityController::class, 'store'])->name('store');
Route::get('/farms/origins/{id}', [CommodityController::class, 'addOriginFarms'])->name('origin-farms.create');
Route::post('/farms/origins/{id}', [CommodityController::class, 'storeOriginFarms'])->name('origin-farms.store');
Route::get('/{commodity}/farms/{farm}', [CommodityController::class, 'showOriginFarmDetails'])
->whereNumber('commodity')
->whereNumber('farm')
->name('origin-farms.show');
Route::get('/{id}', [CommodityController::class, 'show'])->whereNumber('id')->name('show');
Route::get('/batch/create', [BatchController::class, 'create'])->name('batches.create');
Route::post('/batch/store', [CommodityController::class, 'storeBatch'])->name('batch.store');
Route::get('/batch/{id}/edit', [BatchController::class, 'edit'])
->whereNumber('id')
->name('batch.edit');
Route::put('/batch/{id}', [BatchController::class, 'update'])
->whereNumber('id')
->name('batch.update');
Route::delete('/batch/{id}', [BatchController::class, 'destroy'])
->whereNumber('id')
->name('batch.destroy');
Route::get('/batch/verification/{id}', [CommodityController::class, 'verifyBatchCommodities'])->whereNumber('id')->name('batch.verify');
Route::post('/batch/{id}/commodities', [CommodityController::class, 'attachCommodityToBatch'])
->whereNumber('id')
->name('batch.commodities.attach');
Route::post('/batch/{id}/activities', [BatchController::class, 'storeBatchActivity'])
->whereNumber('id')
->name('batch.activities.store');
