<?php


use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\UserProfileController;
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
]);


});


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

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
include_once('admin.php');
});

$subRoutes = ['cooperative', 'buyer'];
foreach ($subRoutes as $subRoute) {
include_once($subRoute.'.php');
}
