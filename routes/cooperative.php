<?php

use App\Http\Controllers\Cooperative\CooperativeController;






Route::middleware(['auth', 'role:farmer'])->group(function () {
// Route::get('/test',[HomeController::class,'userDashboard']);
Route::get('/test',function(){
return('some information11');
});
});


