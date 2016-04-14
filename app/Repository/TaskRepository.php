<?php

namespace App\Repository;

use App\Models\Tasks;
use DB;

class TaskRepository extends AbstractRepository
{
    protected $rules = [
        'description' => 'required|max:200',
        'time' => 'required|numeric',
        'task' => 'required|max:150',
        'date' => 'required|date'
    ];

    protected $table;

    public function __construct()
    {
        $this->table = Tasks::getTableName();
    }

    public function getPending()
    {
        return Tasks::where('status', '<>', Tasks::STATUS_PROCESSED)->get();
    }

}