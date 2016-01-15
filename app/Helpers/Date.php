<?php

namespace App\Helpers;

class Date
{

    /**
     * @var array
     */
    public static $months = [
        '01' => 'jan',
        '02' => 'feb',
        '03' => 'mar',
        '04' => 'apr',
        '05' => 'may',
        '06' => 'jun',
        '07' => 'jul',
        '08' => 'aug',
        '09' => 'sep',
        '10' => 'oct',
        '11' => 'nov',
        '12' => 'dec',
    ];

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