<?php

namespace App\Models\System\Module;

use App\Models\BaseModel;
use App\Models\System\Permission;

/**
 * Class SystemModuleAction
 */
class Action extends BaseModel
{

    protected $table = 'system_module_action';

    public $timestamps = false;

    protected $fillable = [
        'module_id',
        'name',
        'label'
    ];

    public function module()
    {
        return $this->belongsTo('App\Models\System\Module');
    }

    public function profiles()
    {
        return $this->belongsToMany('App\Models\System\Profile', Permission::getTableName());
    }

        
}