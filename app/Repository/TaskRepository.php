<?php

namespace App\Repository;

use App\Helpers\Filter;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Validator;
use DB;

class TaskRepository
{
    const TABLE = 'tasks';

    public static $rules = [
        'description' => 'required|max:200',
        'time' => 'required|integer',
        'task' => 'required|max:150',
        'date' => 'required|date'
    ];

    /**
     * @param Request $request
     * @return bool
     */
    public function validateRequest(Request $request)
    {
        $rules = self::$rules;

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        return true;
    }

    public function findById($id)
    {
        return;
    }

    /**
     * @param Request $request
     * @param int $itemsPage
     * @return mixed
     */
    public function getAllPaginate(Request $request, $itemsPage = 30)
    {
        $query = DB::table(self::TABLE);
        $query = Filter::parse($request, $query);
        $query->orderBy($request->get('sort'), $request->get('order', 'ASC'));

        $paginator = $query->paginate($itemsPage);
        $paginator->appends(app('request')->except('page'));
        return $paginator;
    }
}