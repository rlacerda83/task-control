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

    public function getDatatoDashboard()
    {
        $date = Carbon::now()->subYear(1);
        return DB::table(self::TABLE)
            ->select(
                DB::raw('count(id) as tasks'),
                DB::raw('SUM(time) AS hours'),
                DB::raw('LEFT(date, 7) AS split_date')
            )
            ->where('date', '>=', $date->format('Y-m-d'))
            ->groupBy('split_date')
            ->orderBy('date', 'ASC')
            ->get();
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