<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Helpers\Date;
use App\Repository\ReportsRepository;
use App\Services\Reports;
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
        $reportsService = new Reports();

        $date = Carbon::now()->subYear(1);

        $totalsYear = $this->repository->getTotals($date);
        $graphData = $reportsService->getDataToAppointmentGraph($date);
        $lastTasks = $this->repository->getLastTasks();

        $startDateAppointmentCheck = Carbon::now()->subMonth(1);
        $endDateAppointmentCheck = Carbon::now()->format('Y-m-d');
        $datesWithPendingAppointment = $reportsService->getDaysWithPendingAppointmentHours(
            $startDateAppointmentCheck,
            $endDateAppointmentCheck
        );

        $task = new Tasks();
        $task->status = Tasks::STATUS_PENDING;
        
        return view('dashboard.index')
            ->with('hoursGraph', json_encode($graphData['hoursGraph']))
            ->with('monthGraph', json_encode($graphData['monthGraph']))
            ->with('percentageGraph', json_encode($graphData['percentageGraph']))
            ->with('tasksGraph', json_encode($graphData['tasksGraph']))
            ->with('labelsGraph', json_encode($graphData['labelsGraph']))
            ->with('lastTasks', $lastTasks)
            ->with('statusLabels', self::$statusLabels)
            ->with('task', $task)
            ->with('redirect', 'dashboard')
            ->with('totalsYear', $totalsYear)
            ->with('daysPendingHourGraph', json_encode($datesWithPendingAppointment['hours']))
            ->with('daysPendingHourPendingGraph', json_encode($datesWithPendingAppointment['hoursPending']))
            ->with('daysPendingLabelsGraph', json_encode($datesWithPendingAppointment['date']));
    }
}
