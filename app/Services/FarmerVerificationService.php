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















}
