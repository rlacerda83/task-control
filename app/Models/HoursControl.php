<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoursControl extends Model
{

    protected $table = 'hours_control';

    protected $fillable = ['day', 'time'];

}