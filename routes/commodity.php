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
