<?php

namespace App\Helpers;

use App\Models\Tasks;
use Illuminate\Http\Request;

class Filter {

    const OPERATOR_LIKE = 'lk';
    const OPERATOR_EQUALS = 'eq';
    const OPERATOR_LESS_THAN = 'lte';
    const OPERATOR_MORE_THAN = 'gte';

    public static function parse(Request $request, $query, $primaryTable)
    {
        $params = $request->all();
        if (!isset($params['filter'])) {
            return $query;
        }

        $filters = json_decode($params['filter'], true);

        foreach ($filters as $field => $filter) {
            $fielName = self::getFieldName($field, $primaryTable);
            foreach ($filter as $operator => $value) {

                // todo fix in javascript
                if ($field == 'status') {
                    $value = self::getStatusValue($value);
                }

                switch ($operator) {
                    case self::OPERATOR_LIKE:
                        $query->where($fielName, 'like', "%{$value}%");
                        break;

                    case self::OPERATOR_EQUALS:
                        $query->where($fielName, $value);
                        break;

                    case self::OPERATOR_LESS_THAN:
                        $query->where($fielName, '<', $value);
                        break;

                    case self::OPERATOR_MORE_THAN:
                        $query->where($fielName, '>', $value);
                        break;
                }
            }
        }

        return $query;
    }

    public static function getFieldName($field, $primaryTable)
    {
        if (strpos($field, '-') == 0) {
            return $primaryTable.'.'.$field;
        }

        return str_replace('-', '.', $field);
    }

    public static function getStatusValue($value)
    {
        $arrayStatus = array_merge(Labels::$status, Labels::$statusOrders);
        $status = array_search($value, $arrayStatus);
        return $status;
    }
}