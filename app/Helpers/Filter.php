<?php

namespace App\Helpers;

use App\Models\Tasks;
use Illuminate\Http\Request;

Class Filter {

    const OPERATOR_LIKE = 'lk';
    const OPERATOR_EQUALS = 'eq';
    const OPERATOR_LESS_THAN = 'lte';
    const OPERATOR_MORE_THAN = 'gte';

    public static function parse(Request $request, $query)
    {
        $params = $request->all();
        if (!isset($params['filter'])) {
            return $query;
        }

        $filters = json_decode($params['filter'], true);

        foreach ($filters as $field => $filter) {
            foreach ($filter as $operator => $value) {
                switch ($operator) {
                    case self::OPERATOR_LIKE:
                        $query->where($field, 'like', "%{$value}%");
                        break;

                    case self::OPERATOR_EQUALS:
                        $query->where($field, $value);
                        break;

                    case self::OPERATOR_LESS_THAN:
                        $query->where($field, '<', $value);
                        break;

                    case self::OPERATOR_MORE_THAN:
                        $query->where($field, '>', $value);
                        break;
                }
            }
        }

        return $query;
    }
}