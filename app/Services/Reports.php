<?php

namespace App\Services;

use App\Helpers\Date;
use App\Models\Tasks;
use App\Repository\ReportsRepository;
use App\Repository\TaskRepository;
use Carbon\Carbon;

Class Reports
{
    /**
     * @var ReportsRepository
     */
    protected $reportsRepository;


    public function __construct()
    {
        $this->reportsRepository = new ReportsRepository();
    }

    public function getDataToAppointmentGraph($date)
    {
        $graphData = $this->reportsRepository->getDatatoDashboard($date);

        $hoursGraph = [];
        $tasksGraph = [];
        $labelsGraph = [];
        $monthHours = [];
        $percentageHours = [];
        foreach ($graphData as $data) {
            $auxDate = explode('-', $data->split_date);
            $hoursInMonth = $this->getTotalHoursByMonth($auxDate[0], $auxDate[1]);
            $monthHours[] = $hoursInMonth;
            $percentageHours[] = round(($data->hours / $hoursInMonth) * 100, 2);
            $hoursGraph[] = $data->hours;
            $tasksGraph[] = $data->tasks;
            $labelsGraph[] = Date::$months[$auxDate[1]] . '/' . $auxDate[0];
        }

        return [
            'monthGraph' => $monthHours,
            'hoursGraph' => $hoursGraph,
            'tasksGraph' => $tasksGraph,
            'labelsGraph' => $labelsGraph,
            'percentageGraph' => $percentageHours
        ];
    }

    /**
     * @param $startDateAppointmentCheck
     * @param $endDateAppointmentCheck
     * @return array|mixed
     */
    public function getDaysWithPendingAppointmentHours($startDateAppointmentCheck, $endDateAppointmentCheck)
    {
        $datesWithPendingAppointment = $this->reportsRepository->getDaysWithoutFullHours($startDateAppointmentCheck);
        $period = Date::getDatesFromRange($startDateAppointmentCheck, $endDateAppointmentCheck);

        foreach ($period as $date) {
            if (Date::isWeekend($date)) {
                continue;
            }

            if (Date::isHoliday($date)) {
                continue;
            }

            foreach ($datesWithPendingAppointment as $pendingDay) {
                if ($date == $pendingDay->date) {
                    continue(2);
                }
            }

            $newDate = new \stdClass();
            $newDate->hours = 0;
            $newDate->hoursPending = 8;
            $newDate->date = $date;
            $datesWithPendingAppointment[] = $newDate;
        }

        $this->sortByArrayObjectDate($datesWithPendingAppointment);
        return $this->transformDataToGraph($datesWithPendingAppointment);
    }

    public function getTotalHoursByMonth($year, $month)
    {
        $startDate = Carbon::create($year, $month, 01);
        $endDate = Carbon::create($year, $month, 01)->endOfMonth();
        $period = Date::getDatesFromRange($startDate, $endDate);

        $hours = 0;
        foreach ($period as $date) {
            if (Date::isWeekend($date)) {
                continue;
            }

            if (Date::isHoliday($date)) {
                continue;
            }

            $hours += 8;
        }

        return $hours;
    }

    /**
     * @param $object
     * @return array
     */
    public function transformDataToGraph($object)
    {
        $return = [];
        foreach ($object as $date) {
            $return['hours'][] = $date->hours;
            $return['hoursPending'][] = $date->hoursPending;
            $return['date'][] = substr(Date::conversion($date->date), 0, 5);
        }

        return $return;
    }

    /**
     * @param array $array
     */
    private function sortByArrayObjectDate(array &$array)
    {
        usort($array, function($a, $b)
        {
            $timeA = strtotime($a->date);
            $timeB = strtotime($b->date);

            if ($timeA == $timeB) {
                return 0;
            }

            return $timeA > $timeB ? 1 : -1;
        });
    }
}