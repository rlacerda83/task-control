<?php

namespace App\Services;

use App\Helpers\Date;
use App\Models\HoursControl as HoursControlModel;
use App\Repository\HoursControlRepository;
use Carbon\Carbon;

Class HoursControl
{
    /**
     * @var HoursControlRepository
     */
    protected $repository;


    public function __construct()
    {
        $this->repository = new HoursControlRepository();
    }

    /**
     * @param null $startDate
     * @param null $endDate
     */
    public function getHoursByDate($startDate = null, $endDate = null)
    {
        if ($startDate == null) {
            $startDate = Carbon::now()->firstOfMonth()->format('Y-m-d');
        }

        if ($endDate == null) {
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $hours = $this->repository->getHoursByDate($startDate, $endDate);
        $groupedHours = $this->groupHoursByDate($hours);
        return $this->calculateBalance($groupedHours);
    }

    /**
     * @param $hours
     * @return array
     */
    protected function groupHoursByDate($hours)
    {
        $arrayHours = [];
        foreach ($hours as $hour) {
            $key = Date::conversion($hour->day);
            $arrayHours[$key]['data'][] = [
                'day' => $hour->day,
                'time' => $hour->time
            ];
        }

        return $arrayHours;
    }

    protected function calculateBalance($groupedHours)
    {
        $total = 0;
        foreach ($groupedHours as $date => $arrayDays) {
            foreach ($arrayDays as $day) {
                $dayBalance = $this->calculateBalanceByDay($day);
                $groupedHours[$date]['working_hours'] = gmdate("H:i:s", $dayBalance);
                $groupedHours[$date]['balance'] = $this->getFinalBalanceByDay($dayBalance);
                $groupedHours[$date]['class'] = $dayBalance >= HoursControlModel::WORKING_HOURS_SECONDS ?
                    'success' : 'danger';
                $total += $dayBalance;
            }
        }

        return $groupedHours;
    }

    protected function getFinalBalanceByDay($workingHoursInSeconds)
    {
        if ($workingHoursInSeconds >= HoursControlModel::WORKING_HOURS_SECONDS) {
            return gmdate("H:i:s", $workingHoursInSeconds - HoursControlModel::WORKING_HOURS_SECONDS);
        }

       return '-' . gmdate("H:i:s", HoursControlModel::WORKING_HOURS_SECONDS - $workingHoursInSeconds);
    }


    protected function calculateBalanceByDay($day)
    {
        //first period
        $balance = 0;
        if (isset($day[1])) {
            $first = Carbon::createFromFormat('Y-m-d H:i:s', "{$day[0]['day']} {$day[0]['time']}");
            $second = Carbon::createFromFormat('Y-m-d H:i:s', "{$day[1]['day']} {$day[1]['time']}");

            $balance += $second->diffInSeconds($first);
        }

        //second period
        if (isset($day[3])) {
            $first = Carbon::createFromFormat('Y-m-d H:i:s', "{$day[2]['day']} {$day[2]['time']}");
            $second = Carbon::createFromFormat('Y-m-d H:i:s', "{$day[3]['day']} {$day[3]['time']}");

            $balance += $second->diffInSeconds($first);
        }

        return $balance;
    }

}