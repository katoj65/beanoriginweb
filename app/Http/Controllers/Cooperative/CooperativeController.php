<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CooperativeController extends Controller
{
    //


static function dashboard(){
$data['title']='dashboard';

return Inertia::render('DashboardCooperative',$data);
}













}
