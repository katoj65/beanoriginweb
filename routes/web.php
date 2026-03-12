<?php


use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Batch\BatchController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\UserProfileController;
use App\Http\Controllers\Cooperative\FarmerController;
use App\Http\Controllers\Cooperative\FarmController;
use App\Services\FarmerVerificationService;

Route::get('/', function () {
if (auth()->check()) {
return redirect()->route('dashboard');
}
return Inertia::render('IndexPage', [
'canLogin' => Route::has('login'),
'canRegister' => Route::has('register'),
'laravelVersion' => Application::VERSION,
'phpVersion' => PHP_VERSION,
'marketBoard'=>HomeController::priceIndex(),
'batchListed'=>HomeController::batchesListed(),



]);


});

Route::get('/live-markets', function () {
return Inertia::render('MarketUpdatesLive', [
'marketBoard' => HomeController::priceIndex(),
'batchListed' => HomeController::batchesListed(),
]);
})->name('live.markets');

Route::get('/start-trading', function () {
return Inertia::render('StartTradingPage', [
'marketBoard' => HomeController::priceIndex(),
'batchListed' => HomeController::batchesListed(),
]);
})->name('start.trading');


Route::get('/demo',function(){
return Inertia::render('TestPage');
});


Route::middleware([
'auth:sanctum',
config('jetstream.auth_session'),
'verified',])->group(function () {
Route::get('/dashboard',[HomeController::class,'userDashboard'])->name('dashboard');

//create user profile
Route::post('/store/profile',[UserProfileController::class,'store'])->name('store.profile');
//update user status
Route::post('/update/user-account-status',[UserProfileController::class,'update_user_account_status'])->name('update_user_account_status');




Route::middleware(['auth'])->group(function () {
// Route::get('/test',[HomeController::class,'userDashboard']);
Route::get('/test',function(){
//return Inertia::render('TestPage');

return FarmerVerificationService::checkFarmer();
});
























});
















});





//Register
// Route::get('/register',function(){
// return Inertia::render('RegisterPage');
// });


Route::get('/home',function(){
return Inertia::render('DashboardCooperative');
});




// Route::get('/login',function(){
// return Inertia::render('LoginPage');
// });















//
Route::middleware(['auth'])->prefix('commodity')->name('commodity.')->group(function () {
include_once('commodity.php');
});

Route::middleware(['auth'])->prefix('token')->name('token.')->group(function () {
include_once('token.php');
});

Route::middleware(['auth'])->prefix('buy')->name('buy.')->group(function () {
include_once('buy.php');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
include_once('admin.php');
});


Route::middleware(['auth'])->prefix('market')->name('market.')->group(function(){
include_once('market.php');
});








//farmer prefix
Route::middleware(['auth'])->prefix('farmer')->name('farmer.')->group(function () {
Route::get('/{id}/update', [FarmerController::class, 'farmerUpdatePage'])->whereNumber('id')->name('update.page');
Route::put('/{id}', [FarmerController::class, 'update'])->whereNumber('id')->name('update');
Route::get('/farm/{id}/update', [FarmController::class, 'farmUpdatePage'])->whereNumber('id')->name('farms.update.page');
Route::put('/farm/{id}', [FarmController::class, 'update'])->whereNumber('id')->name('farms.update');
Route::post('/farm/{id}/sustainability-data', [FarmController::class, 'storeFarmSustainabilityData'])->whereNumber('id')->name('farms.sustainability.store');
Route::delete('/farm/{id}/sustainability-data/{sustainabilityId}', [FarmController::class, 'destroySustainabilityData'])->whereNumber('id')->whereNumber('sustainabilityId')->name('farms.sustainability.destroy');
Route::delete('/farm/{id}', [FarmController::class, 'destroy'])->whereNumber('id')->name('farms.destroy');
});





//batch prefix
Route::middleware(['auth'])->prefix('batch')->name('batch.')->group(function () {
Route::post('/{id}/processing', [BatchController::class, 'storeBatchProcessing'])->whereNumber('id')->name('processing.store');
Route::delete('/{id}/processing/{processingId}', [BatchController::class, 'destroyBatchProcessData'])->whereNumber('id')->whereNumber('processingId')->name('processing.destroy');
Route::delete('/{id}/commodities/{commodityId}', [BatchController::class, 'destroyBatchCommodityData'])->whereNumber('id')->whereNumber('commodityId')->name('commodities.destroy');




});









$subRoutes = ['cooperative', 'buyer'];
foreach ($subRoutes as $subRoute) {
include_once($subRoute.'.php');
}
