<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

// Admin dashboard routes.
Route::get('/', [AdminController::class, 'dashboard'])->name('home');
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
Route::get('/tokens/unverified', [AdminController::class, 'unverifiedTokens'])->name('tokens.unverified');
