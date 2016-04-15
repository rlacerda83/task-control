<?php

namespace App\Repository;

use App\Helpers\UserLogged;
use App\Models\Tasks;
use Carbon\Carbon;
use DB;

class ReportsRepository extends AbstractRepository
{
    protected $table;

    /**
     * @var User
     */
    protected $user;

    public function __construct()
    {
        $this->user = UserLogged::get();
        $this->table = Tasks::getTableName();
    }

    /**
     * @param Carbon|null $date
     * @return mixed
     */
    public function getDatatoDashboard($date = null)
    {
        $query = DB::table($this->table)
            ->select(
                DB::raw('count(id) as tasks'),
                DB::raw('SUM(time) AS hours'),
                DB::raw('LEFT(date, 7) AS split_date')
            )
            ->where('user_id', $this->user->id)
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
        return DB::table($this->table)
            ->where('user_id', $this->user->id)
            ->orderBy('id', 'DESC')
            ->limit(10)->get();
    }

    /**
     * @param Carbon|null $date
     * @return mixed
     */
    public function getTotals($date = null)
    {
        $query = DB::table($this->table)
            ->select(
                DB::raw('count(id) as total'),
                DB::raw('SUM(IF(STATUS = \'pending\', 1, 0)) AS totalPending'),
                DB::raw('SUM(IF(STATUS = \'processed\', 1, 0)) AS totalProcessed'),
                DB::raw('SUM(IF(STATUS = \'error\', 1, 0)) AS totalError')
            )
            ->where('user_id', $this->user->id);

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
        $query = DB::table($this->table)
            ->select(
                DB::raw('SUM(time) AS hours'),
                DB::raw('8 - SUM(TIME) AS hoursPending'),
                'date'
            )
            ->where('user_id', $this->user->id)
            ->groupBy('date');
            
        if ($date) {
            $query->where('date', '>=', $date->format('Y-m-d'));
        }

        return $query->get();
    }

}