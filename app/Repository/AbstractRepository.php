<?php

namespace App\Repository;

use App\Helpers\Filter;
use App\Helpers\Validator as ValidatorHelper;
use Illuminate\Http\Request;
use Validator;
use DB;

abstract class AbstractRepository
{
    /**
     * @param Request $request
     * @return bool
     */
    public function validateRequest(Request $request, $returnJson = true)
    {
        $rules = $this->rules;

        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {

            if ($returnJson) {
                return ValidatorHelper::convertErrorsToJson($rules, $validator);
            }

            return $validator->errors()->all();
        }

        return true;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return DB::table($this->table)->where('id', $id)->first();
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute="id")
    {
        return DB::table($this->table)->where($attribute, '=', $id)->update($data);
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
        $query->orderBy($request->get('sort'), $request->get('order', 'ASC'));

        $paginator = $query->paginate($itemsPage);
        $paginator->appends(app('request')->except('page'));
        return $paginator;
    }
}