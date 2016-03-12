<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\ProcessTasks::class,
        Commands\HasPendingHours::class,
        Commands\SetupApplication::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // get jobs
        $schedule->command('queue:work')->everyFiveMinutes()->withoutOverlapping();

        $schedule->command('tasks:pending')->dailyAt('11:00')->withoutOverlapping();

        $schedule->command('tasks:pending')->everyMinute()->withoutOverlapping();
    }
}
