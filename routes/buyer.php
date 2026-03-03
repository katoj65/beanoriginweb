<?php

use App\Http\Controllers\Buyer\BuyerController;

Route::middleware(['auth', 'role:buyer'])
    ->prefix('buyer')
    ->name('buyer.')
    ->group(function () {
        Route::get('/', [BuyerController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [BuyerController::class, 'orders'])->name('orders');
        Route::get('/watchlist', [BuyerController::class, 'dashboard'])->name('watchlist');
        Route::get('/suppliers', [BuyerController::class, 'suppliers'])->name('suppliers');

        Route::get('/profile', [BuyerController::class, 'profile'])->name('profile');
        Route::get('/account-settings', [BuyerController::class, 'accountSettings'])->name('account.settings');
        Route::get('/help', [BuyerController::class, 'help'])->name('help');
        Route::post('/help', [BuyerController::class, 'helpStore'])->name('help.store');

        Route::get('/notifications', [BuyerController::class, 'notifications'])->name('notifications');
    });
