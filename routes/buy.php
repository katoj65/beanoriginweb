<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buy\BuyController;

Route::get('/batch/{id}', [BuyController::class, 'show'])
    ->whereNumber('id')
    ->name('batch.show');

Route::post('/batch/{id}', [BuyController::class, 'store'])
    ->whereNumber('id')
    ->name('batch.store');
