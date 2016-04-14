<?php

namespace App\Models\System;

use App\Models\BaseModel;

/**
 * Class SystemProfile
 */
class Profile extends BaseModel
{

    protected $table = 'system_profile';

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function actions()
    {
        return $this->belongsToMany('App\Models\System\Module\Action', Permission::getTableName());
    }
}