<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Home\HomeController;

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







//user middleware group
Route::middleware(['auth', 'role:user'])->group(function () {
Route::get('/test',[HomeController::class,'userDashboard']);

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
