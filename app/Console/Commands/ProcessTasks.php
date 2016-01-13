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


    protected $tasksProcesseds = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $success = false;
        $password = $this->secret('Enter your password. By default the password is loaded on the configuration.');
        
        try {
            $this->process($password);
            $this->info('Successfully processed tasks.');
            $this->writeTableOutput();
        } catch (\Exception $e) {
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
        $result = $processor->process();

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
