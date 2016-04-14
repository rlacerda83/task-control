<?php

namespace App\Repository;

use App\Helpers\Filter;
use App\Helpers\UserLogged;
use App\Models\HoursControl;
use App\Models\System\User;
use DB;
use Illuminate\Http\Request;

class HoursControlRepository extends AbstractRepository
{
    protected $rules = [
        'time' => 'required',
        'day' => 'required|date'
    ];

    /**
     * @var User
     */
    protected $user;

    protected $table;

    public function __construct()
    {
        $this->user = UserLogged::get();
        $this->table = HoursControl::getTableName();
    }

    public function getHoursByDate($startDate, $endDate)
    {
        $query = DB::table($this->table)
            ->where('user_id', $this->user->id)
            ->whereBetween('day', array($startDate, $endDate))
            ->orderBy('day', 'ASC')
            ->orderBy('time', 'ASC');

        return $query->get();
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