<?php

namespace App\Jobs;

use App\Models\Tasks;
use App\Repository\ConfigurationRepository;
use App\Services\TaskProcessor;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class TaskProcess extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var int
     */
    protected $idTask;

    /**
     * TaskProcess constructor.
     * @param $idTask
     */
    public function __construct($idTask)
    {
        $this->idTask = $idTask;
        DB::reconnect();
    }

    /**
     * @return bool
     */
    public function handle()
    {
        $configurationRepository = new ConfigurationRepository();
        $enableQueueProcess = $configurationRepository->getValue('enable_queue_process');

        $task = Tasks::findOrNew($this->idTask);
        if (!$enableQueueProcess || !$task->id || $task->status != Tasks::STATUS_PENDING) {
            return true;
        }

        $processor = new TaskProcessor();
        $processor->processOneTask($task);
        return true;
    }
}
