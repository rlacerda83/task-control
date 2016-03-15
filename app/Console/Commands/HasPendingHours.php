<?php

namespace App\Console\Commands;

use App\Repository\ConfigurationRepository;
use App\Services\Reports;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;

class HasPendingHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Valid pending hours';

    protected $referenceDate;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment(PHP_EOL. 'Start pending hours validation' .PHP_EOL);

        $referenceDate = Carbon::now();
        $referenceDate->hour(13)->minute(0)->second(0);

        $configurationRepository = new ConfigurationRepository();
        $enableQueueProcess = $configurationRepository->getValue('enable_queue_process');

        $reportsService = new Reports();

        $now = Carbon::now();
        if ($referenceDate->diffInMinutes($now, false) > 0) {
            $date = $now;
        } else {
            $date = Carbon::now()->subDay(1);
        }

        $this->info(PHP_EOL. 'Checking ' . $date->format('d/m/Y') .PHP_EOL);

        $pendingAppointment = $reportsService->getDaysWithPendingAppointmentHours(
            $date,
            $date
        );

        if (isset($pendingAppointment['hoursPending'][0]) && $pendingAppointment['hoursPending'][0] > 0) {
            $notifier = NotifierFactory::create();

            $message = sprintf(
                'You have pending hours in the day %s. You need to send %s hours.',
                $pendingAppointment['date'][0],
                $pendingAppointment['hoursPending'][0]
            );

            $this->error(PHP_EOL. $message .PHP_EOL);

            if ($notifier) {
                // Create your notification
                $notification =
                    (new Notification())
                        ->setTitle('Pending Hours')
                        ->setBody($message)
                        ->setIcon(__DIR__.'/../../../public/img/clock.png')
                ;

                // Send it
                $notifier->send($notification);
            }
        }

        $this->comment(PHP_EOL. 'End pending hours validation' .PHP_EOL);
    }
}
