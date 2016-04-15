<?php

namespace App\Repository;

use App\Helpers\Filter;
use App\Helpers\UserLogged;
use App\Models\System\User;
use App\Models\Tasks;
use DB;
use Illuminate\Http\Request;

class TaskRepository extends AbstractRepository
{
    protected $rules = [
        'description' => 'required|max:200',
        'time' => 'required|numeric',
        'task' => 'required|max:150',
        'date' => 'required|date'
    ];

    protected $table;

    /**
     * @var User
     */
    protected $user;

    public function __construct()
    {
        $this->user = UserLogged::get();
        $this->table = Tasks::getTableName();
    }

    public function getPending()
    {
        return Tasks::where('status', '<>', Tasks::STATUS_PROCESSED)
            ->where('user_id', $this->user->id)
            ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return DB::table($this->table)
            ->where('user_id', $this->user->id)
            ->where('id', $id)
            ->first();
    }

    /**
     * @param Request $request
     * @param int $itemsPage
     * @return mixed
     */
    public function getAllPaginate(Request $request, $itemsPage = 30)
    {
        $query = DB::table($this->table);
        $query = Filter::parse($request, $query, $this->table);
        $query->where('user_id', $this->user->id);
        $query->orderBy($request->get('sort'), $request->get('order', 'ASC'));

        $paginator = $query->paginate($itemsPage);
        $paginator->appends(app('request')->except('page'));
        return $paginator;
    }

}