<?php

namespace App\Models;

use App\Models\Observers\TaskObserver;

class Tasks extends BaseModel
{

    const STATUS_PENDING = 'pending';
    const STATUS_ERROR = 'error';
    const STATUS_PROCESSED = 'processed';

    protected $fillable = ['user_id', 'task', 'time', 'date', 'description', 'status', 'error_message'];

    public static function boot()
    {
        parent::boot();

        Tasks::observe(new TaskObserver);
    }
}