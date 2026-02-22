<?php

use App\Http\Controllers\Cooperative\CooperativeController;
use App\Http\Controllers\Cooperative\ProfileController;
use App\Http\Controllers\Cooperative\AccountSettings;
use App\Http\Controllers\Cooperative\HelpController;
use App\Http\Controllers\Cooperative\FarmerController;
use App\Http\Controllers\Cooperative\FarmController;
use App\Http\Controllers\Produce\ProduceController;
use App\Http\Controllers\Batch\BatchController;
use Inertia\Inertia;






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

Route::get('/cooperative/produce/create/{any}',[ProduceController::class,'create_batch'])->name('cooperative.produce.create.batch');

Route::get('/cooperative/batch/{id}',[ProduceController::class,'show'])->name('cooperative.batch.show');
Route::get('/cooperative/batches/listed',[ProduceController::class,'batchListed'])->name('cooperative.batches.listed');
Route::get('/cooperative/batches/create',[BatchController::class,'create'])->name('cooperative.batches.create');
Route::get('/cooperative/batches/action-page',[BatchController::class,'BatchActionPage'])->name('cooperative.batches.action.page');
Route::get('/cooperative/batches/{id}',[BatchController::class,'show'])->whereNumber('id')->name('cooperative.batches.show');
Route::post('/cooperative/batches',[BatchController::class,'store'])->name('cooperative.batches.store');
Route::post('/cooperative/batches/verification-action',[BatchController::class,'batchVerificationAction'])->name('cooperative.batches.verification.action');


Route::get('/cooperative/notifications', function () {
    return Inertia::render('CooperativeNotificationPage');
})->name('cooperative.notifications');








});
