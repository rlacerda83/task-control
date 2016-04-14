<?php

namespace App\Repository\System\Module;

use App\Models\System\Module\Action;
use App\Models\System\Permission;
use App\Repository\AbstractRepository;
use DB;

class ActionRepository extends AbstractRepository
{
    protected $table;
    protected $tablePermission;

    public function __construct()
    {
        $this->table = Action::getTableName();
        $this->tablePermission = Permission::getTableName();
    }

    public static $rules = [
        'label' => 'required|max:200',
        'name' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return Action::orderBy('module_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }
}