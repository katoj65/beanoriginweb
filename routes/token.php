<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Token\TokenController;

// Token dashboard routes.
Route::get('/', [TokenController::class, 'index'])->name('index');
Route::post('/batch/{id}/tokenize', [TokenController::class, 'tokenize'])->whereNumber('id')->name('batch.tokenize');
