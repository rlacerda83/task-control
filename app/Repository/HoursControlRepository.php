<?php

namespace App\Repository;

use App\Models\HoursControl;
use DB;

class HoursControlRepository extends AbstractRepository
{
    protected $rules = [
        'time' => 'required',
        'day' => 'required|date'
    ];

    protected $table;

    public function __construct()
    {
        $this->table = HoursControl::getTableName();
    }

    public function getHoursByDate($startDate, $endDate)
    {
        $query = DB::table($this->table)
            ->groupBy('split_date')
            ->orderBy('date', 'ASC');

        if ($date) {
            $query->where('date', '>=', $date->format('Y-m-d'));
        }

        return $query->get();
    }

}