<?php

use Illuminate\Support\Facades\Route;

// Commodity-prefixed routes are loaded from routes/web.php under auth middleware.
use App\Http\Controllers\Commodity\CommodityController;

Route::get('/create', [CommodityController::class, 'create'])->name('create');
Route::post('/store', [CommodityController::class, 'store'])->name('store');
Route::get('/farms/origins/{id}', [CommodityController::class, 'addOriginFarms'])->name('origin-farms.create');
// Route::post('/{commodity}/origin-farms', [CommodityController::class, 'addOriginFarms'])->name('origin-farms.store');
