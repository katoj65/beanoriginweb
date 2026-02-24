<?php

use App\Http\Controllers\Buyer\BuyerController;

Route::middleware(['auth', 'role:buyer'])
    ->prefix('buyer')
    ->name('buyer.')
    ->group(function () {
        Route::get('/', [BuyerController::class, 'dashboard'])->name('dashboard');

        Route::get('/market', [BuyerController::class, 'dashboard'])->name('market');
        Route::get('/orders', [BuyerController::class, 'dashboard'])->name('orders');
        Route::get('/watchlist', [BuyerController::class, 'dashboard'])->name('watchlist');
        Route::get('/suppliers', [BuyerController::class, 'dashboard'])->name('suppliers');

        Route::get('/profile', [BuyerController::class, 'dashboard'])->name('profile');
        Route::get('/account-settings', [BuyerController::class, 'dashboard'])->name('account.settings');
        Route::get('/help', [BuyerController::class, 'dashboard'])->name('help');

        Route::get('/notifications', [BuyerController::class, 'dashboard'])->name('notifications');
    });
