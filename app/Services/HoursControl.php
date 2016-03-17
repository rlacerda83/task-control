<?php

namespace App\Services;

use App\Helpers\Date;
use App\Repository\HoursControlRepository;
use App\Repository\ReportsRepository;
use Carbon\Carbon;

Class HoursControl
{
    /**
     * @var HoursControlRepository
     */
    protected $reportsRepository;


    public function __construct()
    {
        $this->reportsRepository = new HoursControlRepository();
    }

    public function getHoursByMonth($date)
    {
        $hours = $this->reportsRepository->getHoursByMonth($date);
    }

}