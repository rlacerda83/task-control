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
        foreach ($graphData as $data) {
            $auxDate = explode('-', $data->split_date);

            $hoursGraph[] = (float) $data->hours;
            $tasksGraph[] = $data->tasks;
            $labelsGraph[] = Date::$months[$auxDate[1]] . '/' . $auxDate[0];
        }

        return [
            'hoursGraph' => $hoursGraph,
            'tasksGraph' => $tasksGraph,
            'labelsGraph' => $labelsGraph
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
        $return = [];
        foreach ($period as $date) {
            if (Date::isWeekend($date)) {
                continue;
            }

            if (Date::isHoliday($date)) {
                continue;
            }

            foreach ($datesWithPendingAppointment as $key => $pendingDay) {
                if ($date == $pendingDay->date) { 
                    if ($pendingDay->hours < 8) {
                        $return[] = $pendingDay;
                        continue(2);    
                    }

                    continue(2);
                }
            }

            $newDate = new \stdClass();
            $newDate->hours = 0;
            $newDate->hoursPending = 8;
            $newDate->date = $date;
            $return[] = $newDate;
        }

        $this->sortByArrayObjectDate($return);
        return $this->transformDataToGraph($return);
    }

    /**
     * @param $object
     * @return array
     */
    public function transformDataToGraph($object)
    {
        $return = [];
        foreach ($object as $date) {
            $return['hours'][] = (float) $date->hours;
            $return['hoursPending'][] = (float) $date->hoursPending;
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