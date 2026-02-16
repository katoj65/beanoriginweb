<?php

use App\Http\Controllers\Cooperative\CooperativeController;
use App\Http\Controllers\Cooperative\ProfileController;
use App\Http\Controllers\Cooperative\AccountSettings;
use App\Http\Controllers\Cooperative\HelpController;
use App\Http\Controllers\Cooperative\FarmerController;






Route::middleware(['auth', 'role:cooperative'])->group(function () {

Route::get('/cooperative/create',[CooperativeController::class,'create_cooperative'])->name('cooperative_create');

Route::post('/store/cooperative',[CooperativeController::class,'store'])->name('cooperative-store');

Route::get('/cooperative/{id}',[CooperativeController::class,'show'])->name('cooperative.show');

Route::get('/cooperate/profile',[ProfileController::class,'show'])->name('cooperative.profile');

Route::get('/cooperate/account-settings',[AccountSettings::class,'index'])->name('cooperative.account.settings');

Route::get('/cooperate/help',[HelpController::class,'index'])->name('cooperative.help');

Route::post('/cooperate/help',[HelpController::class,'store'])->name('cooperative.help.store');

Route::get('/cooperate/farmers',[FarmerController::class,'index'])->name('cooperative.farmers');

Route::get('/cooperate/farmers/create',[FarmerController::class,'create'])->name('cooperative.farmers.create');

Route::post('/cooperate/farmers',[FarmerController::class,'store'])->name('cooperative.farmers.store');












});
