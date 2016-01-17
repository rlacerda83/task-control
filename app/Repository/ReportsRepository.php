<?php

namespace App\Repository;

use App\Helpers\Filter;
use App\Models\Tasks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class ReportsRepository
{
    const TABLE = 'tasks';

    public static $months = [

    ];

    /**
     * @param Carbon|null $date
     * @return mixed
     */
    public function getDatatoDashboard($date = null)
    {
        $query = DB::table(self::TABLE)
            ->select(
                DB::raw('count(id) as tasks'),
                DB::raw('SUM(time) AS hours'),
                DB::raw('LEFT(date, 7) AS split_date')
            )
            ->groupBy('split_date')
            ->orderBy('date', 'ASC');

        if ($date) {
            $query->where('date', '>=', $date->format('Y-m-d'));
        }

        return $query->get();
    }

    /**
     * @return mixed
     */
    public function getLastTasks()
    {
        return DB::table(self::TABLE)->orderBy('id', 'DESC')->limit(10)->get();
    }

    /**
     * @param Carbon|null $date
     * @return mixed
     */
    public function getTotals($date = null)
    {
        $query = DB::table(self::TABLE)
            ->select(
                DB::raw('count(id) as total'),
                DB::raw('SUM(IF(STATUS = \'pending\', 1, 0)) AS totalPending'),
                DB::raw('SUM(IF(STATUS = \'processed\', 1, 0)) AS totalProcessed'),
                DB::raw('SUM(IF(STATUS = \'error\', 1, 0)) AS totalError')
            );

        if ($date) {
            $query->where('date', '>=', $date->format('Y-m-d'));
        }

        return $query->first();
    }

    /**
     * @param Carbon|null $date
     * @return mixed
     */
    public function getDaysWithoutFullHours($date = null)
    {
        $query = DB::table(self::TABLE)
            ->select(
                DB::raw('SUM(time) AS hours'),
                DB::raw('8 - SUM(TIME) AS hoursPending'),
                'date'
            )->groupBy('date')
            ->having('hours', '<', 8);

        if ($date) {
            $query->where('date', '>=', $date->format('Y-m-d'));
        }

        return $query->get();
    }

}