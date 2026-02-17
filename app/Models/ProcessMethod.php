<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessMethod extends Model
{
    //
    protected $table='process_methods';
    protected $fillable=['name','description'];

}
