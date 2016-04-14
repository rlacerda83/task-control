<?php

namespace App\Helpers;


class ParserGrid {

    const SEPARATOR = '_';

    /**
     * @param $params
     * @param $reference
     * @return array
     */
    public static function parse($params, $reference)
    {
        $cleanParams = self::removeGenericParams($params, $reference);

        return self::separateArray($cleanParams);
    }

    /**
     * @param $params
     * @param $reference
     * @return array
     */
    protected static function removeGenericParams($params, $reference)
    {
        $cleanParams = [];
        foreach ($params as $key => $value) {
            if (stristr($key, $reference)) {
                $newKey = str_replace($reference . self::SEPARATOR, '', $key);
                $cleanParams[$newKey] = $value;
            }
        }

        return $cleanParams;
    }

    /**
     * @param $params
     * @return array
     */
    protected static function separateArray($params)
    {
        unset($params['rowOrder']);
        $separatedArray = [];
        foreach ($params as $key => $value) {
            $explodedKey = explode(self::SEPARATOR, $key);
            $indexKey = array_pop($explodedKey);
            $newField = implode('_', $explodedKey);

            $separatedArray[$indexKey][$newField] = $value;
        }

        return $separatedArray;
    }
}