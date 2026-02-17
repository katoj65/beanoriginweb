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
return Inertia::render('Welcome', [
'canLogin' => Route::has('login'),
'canRegister' => Route::has('register'),
'laravelVersion' => Application::VERSION,
'phpVersion' => PHP_VERSION,
]);


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



include_once('cooperative.php');
