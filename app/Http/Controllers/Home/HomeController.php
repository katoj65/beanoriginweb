<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use App\Http\Controllers\Cooperative\CooperativeController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Admin\AdminController;


class HomeController extends Controller
{
    //

public function userDashboard(Request $request){
$user = $request->user();
$userProfile = UserProfile::where('user_id', $user->id)->first();
$userRoles=UserRoles::where('role','!=','admin')->get();

if($user->role=='cooperative'){
$cooperative=new CooperativeController;
return $cooperative->dashboard();
}else if($user->role=='buyer'){
return BuyerController::dashboard();
}else if($user->role=='admin'){
return AdminController::dashboard();
}






return Inertia::render('Dashboard', [
'title' => 'dashboard',
'response' => [
'user' => $user,
'user_profile_exists' => (bool) $userProfile,
'user_profile' => $userProfile,
'user_roles'=>$userRoles,
],
]);


}
















}
