<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Repository\ReportsRepository;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class DashboardController extends BaseController
{

    public static $statusLabels = [
        Tasks::STATUS_PENDING => 'warning',
        Tasks::STATUS_PROCESSED => 'success',
        Tasks::STATUS_ERROR => 'danger'
    ];

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
        $date = Carbon::now()->subYear(1);
        $totalsYear = $this->repository->getTotals($date);
        $graphData = $this->repository->getDatatoDashboard($date);

        $hoursGraph = [];
        $tasksGraph = [];
        $labelsGraph = [];
        foreach ($graphData as $data) {
            $hoursGraph[] = $data->hours;
            $tasksGraph[] = $data->tasks;
            $labelsGraph[] = $data->split_date;
        }

        $lastTasks = $this->repository->getLastTasks();

        $task = new Tasks();
        $task->status = Tasks::STATUS_PENDING;

        return view('dashboard.index')
            ->with('hoursGraph', json_encode($hoursGraph))
            ->with('tasksGraph', json_encode($tasksGraph))
            ->with('labelsGraph', json_encode($labelsGraph))
            ->with('lastTasks', $lastTasks)
            ->with('statusLabels', self::$statusLabels)
            ->with('task', $task)
            ->with('redirect', 'dashboard')
            ->with('totalsYear', $totalsYear);
    }
}
