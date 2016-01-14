<?php

namespace App\Models;

use App\Models\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{

    const STATUS_PENDING = 'pending';
    const STATUS_ERROR = 'error';
    const STATUS_PROCESSED = 'processed';

    protected $fillable = ['task', 'time', 'date', 'description', 'status', 'error_message'];

    public static function boot()
    {
        parent::boot();

        Tasks::observe(new TaskObserver);
    }
}