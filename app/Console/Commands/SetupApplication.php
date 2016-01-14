<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class SetupApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup application';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->info('Setting up database migrations...');

            $bar = $this->output->createProgressBar(100);
            $bar->setFormat('debug');

            $this->createDatabse();
            $bar->advance();

            //$this->call('migrate:install');
            $bar->advance();

            $this->call('migrate');
            $bar->advance();

            $this->info(' '. "\n");
            //shell_exec('composer install');
            $bar->advance();
            $this->info(' '. "\n");


            if ($this->confirm('Do you wish run the queue with supervisord?')) {
                $this->info('Installing supervisor...');
                $this->setupSupervisord();
            }
            $bar->advance();

//            $this->info(' '. "\n");
//            if ($this->confirm('Do you wish run the queue with cron?')) {
//                $this->info('Setup cron...');
//            }
//            $bar->advance();

            //shell_exec('mv .env.example  .env.');
            //$bar->advance();

            $bar->finish();
            $this->info(' '. "\n");
            DB::commit();
        } catch (\Exception $e) {
            $this->info(' '. "\n");
            $this->error('Erro:' . $e->getMessage());
            DB::rollBack();
        }
    }

    private function createDatabse()
    {
        DB::statement(
            'CREATE DATABASE IF NOT EXISTS ' . env('DB_DATABASE', 'task_control')
        );
    }

    private function setupSupervisord()
    {
        exec('sudo apt-get install -y supervisor || (echo "Error Installing supervisord"; exit 1;)');
        exec('sudo rm -f /etc/supervisor/conf.d/task_control.conf');
        exec('sudo touch /etc/supervisor/conf.d/task_control.conf');

        $phpPath = exec('which php');
        $baseDir = str_replace('/app', '', app_path());
        $username = exec('whoami');

        $config = "
[program:task-control-queue-listen]
command={$phpPath} {$baseDir}/artisan queue:listen --sleep=3 --tries=3
user={$username}
process_name=%(program_name)s_%(process_num)d
directory={$baseDir}
stdout_logfile={$baseDir}/storage/logs/supervisor.log
redirect_stderr=true
autostart=true
autorestart=true
startretries=3
numprocs=2";

        //$this->info($config);


        exec("sudo sh -c 'echo \"$config\" >> /etc/supervisor/conf.d/task_control.conf'");
        exec('sudo service supervisor restart');

    }

    private function setupCronTab()
    {

    }
}
