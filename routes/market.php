<?php

use App\Http\Controllers\Buyer\BuyerController;

Route::middleware(['auth', 'role:buyer'])
    ->prefix('buyer')
    ->name('buyer.')
    ->group(function () {
        Route::get('/market', [BuyerController::class, 'market'])->name('market');
        Route::get('/market/{id}', [BuyerController::class, 'marketShow'])->whereNumber('id')->name('market.show');
        Route::get('/market/{id}/buy', [BuyerController::class, 'buyBlockPage'])->whereNumber('id')->name('market.buy');
        Route::post('/market/{id}/bid', [BuyerController::class, 'storeBid'])->whereNumber('id')->name('market.bid.store');
    });
