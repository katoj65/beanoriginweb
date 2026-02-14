<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    protected $table = 'cooperative';

    protected $fillable = [
        'legal_name',
        'name',
        'reg_num',
        'reg_date',
        'district',
        'physical_address',
        'postal_address',
        'email',
        'tel',
        'website',
        'user_id',
    ];
}
