<?php

namespace App\Repository;

use App\Helpers\Filter;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Validator;
use DB;

class HoursControlRepository
{
    const TABLE = 'hours_control';

    public static $rules = [
        'time' => 'required',
        'day' => 'required|date'
    ];

    /**
     * @param Request $request
     * @return bool
     */
    public function validateRequest(Request $request, $returnJson = true)
    {
        $rules = self::$rules;

        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {

            if ($returnJson) {
                return $this->convertErrorsToJson($validator);
            }

            return $validator->errors()->all();
        }

        return true;
    }

    protected function convertErrorsToJson($validator)
    {
        $errors = [];
        foreach (self::$rules as $field => $value) {
            foreach ($validator->messages()->get($field) as $message) {
                $errors[$field] = $message;
            }
        }

        return json_encode($errors);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return DB::table(self::TABLE)->where('id', $id)->first();
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute="id")
    {
        return DB::table(self::TABLE)->where($attribute, '=', $id)->update($data);
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

    public function getHoursByDate($startDate, $endDate)
    {
        $query = DB::table(self::TABLE)
            ->whereBetween('day', array($startDate, $endDate))
            ->orderBy('day', 'ASC')
            ->orderBy('time', 'ASC');

        return $query->get();
    }

}