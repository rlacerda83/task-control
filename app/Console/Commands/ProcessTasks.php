<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TaskProcessor;
use App\Helpers\Date;

class ProcessTasks extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes all pending tasks';

    protected $bar;

    /**
     * @var array
     */
    protected $tasksProcesseds = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $success = false;
        $password = $this->secret('Enter your password:');

        $this->bar = $this->output->createProgressBar();
        $this->bar->setFormat('debug');

        try {
            $this->process($password);

            $this->bar->finish();
            $this->info(' '. "\n");
            $this->info('Successfully processed tasks.');
            $this->writeTableOutput();
        } catch (\Exception $e) {
            $this->info(' '. "\n");
            $this->error($e->getMessage());
            $success = false;
        }

        return $success;
    }

    private function writeTableOutput()
    {
        $headers = ['Tasks', 'Date', 'Time', 'Description', 'Status'];
        $tasks = $this->tasksProcesseds;

        $this->table($headers, $tasks);
    }

    private function process($password)
    {
        $processor = new TaskProcessor($password);
        $result = $processor->process($this->bar);

        if ($result) {
            $tasksProcesseds = $processor->getTasksProcessed();
            foreach ($tasksProcesseds as $task) {
                $this->tasksProcesseds[] = [
                    $task->task,
                    Date::conversion($task->date),
                    $task->time,
                    $task->description,
                    $task->status
                ];
            }  
        }
    }
}
