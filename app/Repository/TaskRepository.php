<?php

namespace App\Repository;
use Illuminate\Http\Request;
use Validator;

class TaskRepository
{

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

    public function getAll(Request $request, $itemsPage = 30)
    {
        return Tasks::all();
    }
}