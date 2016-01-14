<?php

namespace App\Models\Observers;

use App\Models\Tasks;
use App\Jobs\TaskProcess;
use App\Repository\ConfigurationRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;

class TaskObserver
{
    use DispatchesJobs;


    /**
     * @param Tasks $task
     * @return bool
     */
    public function saved(Tasks $task)
    {
        $configurationRepository = new ConfigurationRepository();
        $enableQueueProcess = $configurationRepository->getValue('enable_queue_process');

        if ($task->status != Tasks::STATUS_PENDING || !$enableQueueProcess) {
            return true;
        }

        $this->dispatch(new TaskProcess($task->id));
        return true;
    }
}