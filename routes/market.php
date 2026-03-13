<?php

use App\Http\Controllers\Batch\BatchController;
use App\Http\Controllers\Market\BiddingController;
use App\Http\Controllers\Market\MarketController;

Route::get('/', [MarketController::class, 'index'])->name('index');
Route::get('/bidding', [BiddingController::class, 'index'])->name('bidding');
// Route::get('/batch-bidding/{id}', [BiddingController::class, 'showBatchBidding'])->whereNumber('id')->name('batchBidding');
Route::post('/batch-bidding/{id}/store', [BiddingController::class, 'storeBatchBidding'])->whereNumber('id')->name('batchBidding.store');
Route::delete('/batch-bidding/{id}/withdraw', [MarketController::class, 'destroyUserBid'])->whereNumber('id')->name('batchBidding.withdraw');
Route::get('/bids/user', [BiddingController::class, 'userBids'])->name('bids.user');
Route::get('/reserved', [MarketController::class, 'reservedMarket'])->name('reserved');
Route::get('/bought', [MarketController::class, 'boughtMarket'])->name('bought');
Route::get('/batch/saved', [BatchController::class, 'batchSaved'])->name('batchSaved');
Route::get('/batch/{id}/saved', [BatchController::class, 'showBatchSaved'])->whereNumber('id')->name('batchSaved.show');
Route::get('/cart', [MarketController::class, 'shoppingCart'])->name('cart.index');
Route::get('/checkout', [MarketController::class, 'checkout'])->name('checkout');
Route::post('/checkout/store', [MarketController::class, 'storeCheckout'])->name('checkout.store');
Route::get('/purchase-confirmation', [MarketController::class, 'purchaseConfirmation'])->name('purchaseConfirmation');
Route::get('/batch/{id}', [MarketController::class, 'show'])->whereNumber('id')->name('show');
Route::post('/cart/store', [MarketController::class, 'storeNewCart'])->name('cart.store');
Route::delete('/cart/{id}', [MarketController::class, 'destroyCartItem'])->whereNumber('id')->name('cart.destroy');
