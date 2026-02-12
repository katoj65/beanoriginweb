<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Home\HomeController;

Route::get('/', function () {
return Inertia::render('Welcome', [
'canLogin' => Route::has('login'),
'canRegister' => Route::has('register'),
'laravelVersion' => Application::VERSION,
'phpVersion' => PHP_VERSION,
]);
// return Inertia::render('LoginPage');
});






Route::middleware([
'auth:sanctum',
config('jetstream.auth_session'),
'verified',
])->group(function () {


Route::get('/dashboard', function () {
return Inertia::render('Dashboard');
})->name('dashboard');




Route::get('/test',[HomeController::class,'userDashboard']);





















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
