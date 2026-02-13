<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use app\Models\UserRoles;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //

public function userDashboard(Request $request){
$user=$request->user();
return Inertia::render('Dashboard',[
'title'=>'dashboard',
'response'=>['user'=>$user]

]);
}









}
