<?php

namespace App\Helpers;

class Date
{
    /**
     * Conversor o formato da data de dd/mm/aaaa para aaaa/mm/dd e vice-versa
     *
     * @param string $date dd/mm/aaaa | aaaa-mm-dd [yy:m:ss]
     * @return string
     */
    public static function conversion($date)
    {
        if (strlen($date)) {
            $time = null;
            if (strlen($date) > 10) {
                $time = substr($date, 10);
                $date = substr($date, 0, 10);
            }

            $token = strpos($date, '/') ? '/' : '-';
            $tmp = explode($token, $date);
            foreach ($tmp as &$val) {
                $val = str_pad($val, 2, 0, STR_PAD_LEFT);
            }

            return trim(implode(($token == '-' ? '/' : '-'), array_reverse($tmp)) . " {$time}");
        }
    }
}