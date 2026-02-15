<?php

use App\Http\Controllers\Cooperative\CooperativeController;
use App\Http\Controllers\Cooperative\ProfileController;






Route::middleware(['auth', 'role:cooperative'])->group(function () {

Route::get('/cooperative/create',[CooperativeController::class,'create_cooperative'])->name('cooperative_create');

Route::post('/store/cooperative',[CooperativeController::class,'store'])->name('cooperative-store');

Route::get('/cooperative/{id}',[CooperativeController::class,'show'])->name('cooperative.show');

Route::get('/cooperate/profile',[ProfileController::class,'show'])->name('cooperative.profile');












});

