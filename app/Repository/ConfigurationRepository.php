<?php

namespace App\Repository;

use App\Helpers\Filter;
use Illuminate\Http\Request;
use Validator;
use DB;

class ConfigurationRepository
{
    const TABLE = 'configuration';

    public static $rules = [
        'email' => 'required|email',
        'url_form' => 'required|url',
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
     * @return mixed
     */
    public function findFirst()
    {
        return DB::table(self::TABLE)->orderBy('id')->first();
    }

    public function getValue($value)
    {
        $configuration = $this->findFirst();
        return isset($configuration->$value) ? $configuration->$value : null;
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
}