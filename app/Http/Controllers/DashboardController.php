<?php

namespace App\Http\Controllers;

use App\Repository\ReportsRepository;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class DashboardController extends BaseController
{

    /**
     * @var ReportsRepository
     */
    private $repository;

    /**
     * @param ReportsRepository $repository
     */
    public function __construct(ReportsRepository $repository)
    {
        $this->repository = $repository;
    }


    public function indexAction()
    {
        $graphData = $this->repository->getDatatoDashboard();

        $hoursGraph = [];
        $tasksGraph = [];
        $labelsGraph = [];
        foreach ($graphData as $data) {
            $hoursGraph[] = $data->hours;
            $tasksGraph[] = $data->tasks;
            $labelsGraph[] = $data->split_date;
        }
        
        return view('dashboard.index')
            ->with('hoursGraph', json_encode($hoursGraph))
            ->with('tasksGraph', json_encode($tasksGraph))
            ->with('labelsGraph', json_encode($labelsGraph));
    }
}
