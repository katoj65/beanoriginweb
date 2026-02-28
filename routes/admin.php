<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

// Admin dashboard routes.
Route::get('/', [AdminController::class, 'dashboard'])->name('home');
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
Route::get('/tokens/unverified', [AdminController::class, 'unverifiedTokens'])->name('tokens.unverified');
Route::get('/batch/verification/{id}', [AdminController::class, 'batchVerification'])->whereNumber('id')->name('batch.verification');
Route::post('/batch/verification/{id}', [AdminController::class, 'verifyBatch'])->whereNumber('id')->name('batch.verify');
