<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropType extends Model
{
    protected $table = 'crop_types';

    protected $fillable = [
        'name',
        'description',
    ];
}
