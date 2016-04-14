<?php

namespace App\Models;

class HoursControl extends BaseModel
{

    const WORKING_HOURS = 8;
    const WORKING_HOURS_SECONDS = 28800;

    protected $table = 'hours_control';

    protected $fillable = ['day', 'user_id', 'time'];

}