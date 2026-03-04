<?php

use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Market\MarketController;

Route::get('/', [MarketController::class, 'index'])->name('index');
Route::get('/reserved', [MarketController::class, 'reservedMarket'])->name('reserved');
Route::get('/bought', [MarketController::class, 'boughtMarket'])->name('bought');
Route::get('/requests', [MarketController::class, 'marketRequests'])->name('requests');
Route::get('/cart', [MarketController::class, 'shoppingCart'])->name('cart.index');
Route::get('/batch/{id}', [MarketController::class, 'show'])->whereNumber('id')->name('show');
Route::post('/cart/store', [MarketController::class, 'storeNewCart'])->name('cart.store');



// Route::middleware(['auth', 'role:buyer'])
//     ->prefix('buyer')
//     ->name('buyer.')
//     ->group(function () {

//         Route::get('/market', [BuyerController::class, 'market'])->name('market');
//         Route::get('/market/{id}', [BuyerController::class, 'marketShow'])->whereNumber('id')->name('market.show');
//         Route::get('/market/{id}/buy', [BuyerController::class, 'buyBlockPage'])->whereNumber('id')->name('market.buy');
//         Route::post('/market/{id}/bid', [BuyerController::class, 'storeBid'])->whereNumber('id')->name('market.bid.store');


//     });
