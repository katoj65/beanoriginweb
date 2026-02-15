<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use app\Models\UserRoles;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use App\Http\Controllers\Cooperative\CooperativeController;

class HomeController extends Controller
{
    //

public function userDashboard(Request $request){
$user = $request->user();
$userProfile = UserProfile::where('user_id', $user->id)->first();


if($user->role=='cooperative'){
$cooperative=new CooperativeController;
return $cooperative->dashboard();
}






return Inertia::render('Dashboard', [
'title' => 'dashboard',
'response' => [
'user' => $user,
'user_profile_exists' => (bool) $userProfile,
'user_profile' => $userProfile,
],
]);


}
















}
