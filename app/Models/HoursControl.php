<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoursControl extends Model
{

    const WORKING_HOURS = 8;
    const WORKING_HOURS_SECONDS = 28800;

    protected $table = 'hours_control';

    protected $fillable = ['day', 'time'];

}