<?php

namespace App\Repository\System;

use App\Models\System\Module\Action;
use App\Models\System\Permission;
use App\Models\System\Profile;
use App\Repository\AbstractRepository;
use DB;

class ProfileRepository extends AbstractRepository
{
    protected $table;
    protected $tablePermission;
    protected $tableAction;

    public function __construct()
    {
        $this->table = Profile::getTableName();
        $this->tablePermission = Permission::getTableName();
        $this->tableAction = Action::getTableName();
    }

    protected $rules = [
        'name' => 'required|max:200'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return DB::table($this->table)->orderBy('name', 'asc')->lists('name','id');
    }

    public function hasPermission($profile, $action)
    {
        $permissions = $this->getPermissionsById($profile->id);

        $response = false;
        foreach ($permissions as $permission) {
            if ($permission->id === $action->id) {
                $response = true;
                break;
            }
        }
        return $response;
    }

    /**
     * @param $idProfile
     * @return mixed
     */
    public function getPermissionsById($idProfile)
    {
        $fields = [
            "{$this->tableAction}.*"
        ];

        return DB::table($this->table)
            ->select($fields)
            ->join($this->tablePermission, "{$this->tablePermission}.profile_id", '=', "{$this->table}.id")
            ->join($this->tableAction, "{$this->tablePermission}.action_id", '=', "{$this->tableAction}.id")
            ->where("{$this->table}.id", $idProfile)
            ->get();
    }
}