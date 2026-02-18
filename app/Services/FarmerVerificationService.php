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
$expiry_minutes=$valid->expiry_minutes;
$date=$valid?->created_at?->format('Ymd');
$time=$valid?->created_at?->format('ih');
$hour=$valid?->created_at?->format('h');
$minute=$valid?->created_at?->format('i');

$currentDate=now()->format('Ymd');
$currentTime=now()->format('ih');
$currentHour=now()->format('h');
$currentMinute=now()->format('i');

$status='valid';
//compare date
if($date==$currentDate){
//compare difference in minutes.
if($hour==$currentHour){
//if the difference is greater than 5 minutes then it is invalid
$dif=$currentMinute-$minute;
if($dif<=$expiry_minutes){
$status='valid';
}

}else{
$status='invalid';
}

}else{
$status='invalid';
}

return $status;

}













}
