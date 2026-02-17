<?php

use App\Http\Controllers\Cooperative\CooperativeController;
use App\Http\Controllers\Cooperative\ProfileController;
use App\Http\Controllers\Cooperative\AccountSettings;
use App\Http\Controllers\Cooperative\HelpController;
use App\Http\Controllers\Cooperative\FarmerController;
use App\Http\Controllers\Cooperative\FarmController;
use App\Http\Controllers\Produce\ProduceController;






Route::middleware(['auth', 'role:cooperative'])->group(function () {

Route::get('/cooperative/create',[CooperativeController::class,'create_cooperative'])->name('cooperative_create');

Route::post('/store/cooperative',[CooperativeController::class,'store'])->name('cooperative-store');

Route::get('/cooperative/{id}',[CooperativeController::class,'show'])
    ->whereNumber('id')
    ->name('cooperative.show');

Route::get('/cooperate/profile',[ProfileController::class,'show'])->name('cooperative.profile');

Route::get('/cooperate/account-settings',[AccountSettings::class,'index'])->name('cooperative.account.settings');

Route::get('/cooperate/help',[HelpController::class,'index'])->name('cooperative.help');

Route::post('/cooperate/help',[HelpController::class,'store'])->name('cooperative.help.store');

Route::get('/cooperate/farmers',[FarmerController::class,'index'])->name('cooperative.farmers');

Route::get('/cooperate/farmers/create',[FarmerController::class,'create'])->name('cooperative.farmers.create');

Route::get('/cooperate/farmers/{id}',[FarmerController::class,'show'])->name('cooperative.farmers.show');

Route::post('/cooperate/farmers',[FarmerController::class,'store'])->name('cooperative.farmers.store');

Route::get('/cooperate/farms/create',[FarmController::class,'create'])->name('cooperative.farms.create');

Route::post('/cooperate/farms',[FarmController::class,'store'])->name('cooperative.farms.store');

Route::get('/cooperate/farms/{id}',[FarmController::class,'show'])->name('cooperative.farms.show');

Route::get('/cooperative/produce',[ProduceController::class,'index'])->name('cooperative.produce');

Route::get('/cooperative/produce/create',[ProduceController::class,'create'])->name('cooperative.produce.create');

Route::post('/cooperative/produce',[ProduceController::class,'store'])->name('cooperative.produce.store');

Route::post('/verification/farmer/produce',[ProduceController::class,'store_verification'])->name('verification.farmer.store');







});
