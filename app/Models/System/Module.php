<?php

namespace App\Models\System;

use App\Models\BaseModel;

/**
 * Class SystemModule
 */
class Module extends BaseModel
{

    protected $table = 'system_module';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'label'
    ];

    public function actions()
    {
    	return $this->hasMany('App\Models\System\Module\Action');
    }

}