<?php

namespace App\Services;

use App\Models\FarmerBatchVerification;
use App\Models\Produce;
use Illuminate\Support\Str;


class FarmerVerificationService{


static function generate_verification_code(){
$code = strtoupper(Str::random(5));
return $code;
}





static function check_id_validity($id){
$valid=FarmerBatchVerification::where('verification_id',$id)->first();
if($valid->status=='expired'){
return ['status' => false];
}

$expiry_minutes=$valid->expiry_minutes;
$expiry_date=$valid->created_at->addMinutes($expiry_minutes);
$now=now();
return ['status' => $now->lt($expiry_date)];

}













}
