<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropGrade extends Model
{
    protected $table = 'crop_grades';

    protected $fillable = [
        'name',
        'description',
    ];
}
