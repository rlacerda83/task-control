<?php

namespace App\Services;

use App\Helpers\Date;
use App\Repository\ReportsRepository;
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
        $hoursService = new HoursControl();

        $hoursGraph = [];
        $tasksGraph = [];
        $labelsGraph = [];
        $monthHours = [];
        $percentageHours = [];
        $eletronicPointHours = [];
        foreach ($graphData as $data) {
            $dataBase = $data->split_date . '-01';
            $dataBase = Carbon::createFromFormat('Y-m-d', $dataBase);

            $pointHours = $hoursService->getHoursByDate(
                $dataBase->firstOfMonth()->format('Y-m-d'),
                $dataBase->endOfMonth()->format('Y-m-d'),
                true
            );

            $pointHours = str_replace(':', '.', $pointHours);
            $eletronicPointHours[] = substr($pointHours, 0, 5);

            $auxDate = explode('-', $data->split_date);
            $hoursInMonth = $this->getTotalHoursByMonth($auxDate[0], $auxDate[1]);
            $monthHours[] = $hoursInMonth;
            $percentageHours[] = round(($data->hours / $hoursInMonth) * 100, 2);
            $hoursGraph[] = $data->hours;
            $tasksGraph[] = $data->tasks;
            $labelsGraph[] = Date::$months[$auxDate[1]] . '/' . $auxDate[0];
        }

        return [
            'eletronicPointHours' => $eletronicPointHours,
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
     * @param $year
     * @param $month
     * @return int
     */
    public function getTotalHoursByMonth($year, $month)
    {
        $startDate = Carbon::create($year, $month, 01);
        $endDate = Carbon::create($year, $month, 01)->endOfMonth();
        $period = Date::getDatesFromRange($startDate, $endDate);

        return $this->getWorkingHoursByPeriod($period);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return int
     */
    public function getTotalWorkingHoursByDate($startDate, $endDate)
    {
        $period = Date::getDatesFromRange($startDate, $endDate);

        return $this->getWorkingHoursByPeriod($period);
    }

    /**
     * @param $period
     * @return int
     */
    protected function getWorkingHoursByPeriod($period)
    {
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