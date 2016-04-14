<?php

namespace App\Models\System;

use App\Models\BaseModel;

/**
 * Class SystemPermission
 */
class Permission extends BaseModel
{

    protected $table = 'system_permission';

    public $timestamps = false;

    protected $fillable = [
        'profile_id',
        'permission_id'
    ];

}