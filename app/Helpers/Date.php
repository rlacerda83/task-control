<?php

namespace App\Helpers;

use Carbon\Carbon;

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

        return false;
    }

    /**
     * @param $year
     * @return array
     */
    public static function Holidays($year)
    {
        $day = 86400;
        $dates = array();
        $dates['easter'] = easter_date($year);
        $dates['good_friday'] = $dates['easter'] - (2 * $day);
        $dates['carnival'] = $dates['easter'] - (47 * $day);
        $dates['corpus_christi'] = $dates['easter'] + (60 * $day);
        $holidays = array (
            Carbon::create($year, 1, 1)->format('Y-m-d'),
            date('Y-m-d', $dates['carnival']),
            date('Y-m-d', $dates['good_friday']),
            Carbon::create($year, 4, 21)->format('Y-m-d'),
            Carbon::create($year, 5, 1)->format('Y-m-d'),
            date('Y-m-d', $dates['corpus_christi']),
            Carbon::create($year, 7, 9)->format('Y-m-d'),
            Carbon::create($year, 9, 7)->format('Y-m-d'),
            Carbon::create($year, 10, 12)->format('Y-m-d'),
            Carbon::create($year, 11, 2)->format('Y-m-d'),
            Carbon::create($year, 11, 15)->format('Y-m-d'),
            Carbon::create($year, 11, 20)->format('Y-m-d'),
            Carbon::create($year, 12, 25)->format('Y-m-d'),
        );

        return $holidays;
    }

    /**
     * @param $date
     * @return bool
     */
    public static function isHoliday($date)
    {
        $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
        $holydays = self::Holidays($carbonDate->year);
        if (in_array($date, $holydays)) {
            return true;
        }

        return false;
    }

    /**
     * @param $date
     * @return bool
     */
    public static function isWeekend($date)
    {
        $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
        $dayOfWeek = $carbonDate->dayOfWeek;

        if ($dayOfWeek == 0 || $dayOfWeek == 6) {
            return true;
        }

        return false;
    }

    /**
     * @param $fromDate
     * @param $toDate
     * @return array
     */
    public static function getDatesFromRange($fromDate, $toDate)
    {
        $start = Carbon::createFromFormat('Y-m-d', substr($fromDate, 0, 10));
        $end = Carbon::createFromFormat('Y-m-d', substr($toDate, 0, 10));

        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy()->format('Y-m-d');
            $start->addDay();
        }

        return $dates;
    }
}