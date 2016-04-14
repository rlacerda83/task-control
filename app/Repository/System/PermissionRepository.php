<?php

namespace App\Repository\System;

use App\Models\System\Module\Action;
use App\Models\System\Permission;
use App\Models\System\Profile;
use App\Repository\AbstractRepository;
use DB;

class PermissionRepository extends AbstractRepository
{
    protected $table;
    protected $tableProfile;
    protected $tableAction;

    public function __construct()
    {
        $this->table = Permission::getTableName();
        $this->tableProfile = Profile::getTableName();
        $this->tableAction = Action::getTableName();
    }

    protected $rules = [
        'action_id' => 'required',
        'profile_id' => 'required'
    ];

    public function removeByProfileAndAction($idProfile, $idAction)
    {

    }
}