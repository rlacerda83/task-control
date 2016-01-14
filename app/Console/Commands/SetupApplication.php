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

    protected $phpPath;

    protected $baseDir;

    protected $userName;

    protected $bar;


    public function __construct()
    {
        parent::__construct();
        $this->phpPath = exec('which php');
        $this->baseDir = str_replace('/app', '', app_path());
        $this->userName = exec('whoami');
    }

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

            $this->bar = $this->output->createProgressBar(10);
            $this->bar->setFormat('debug');

            $this->info('Creating databse...');
            $this->createDatabase();
            $this->advanceBar();

            $this->info('Installing migrations...');
            //$this->call('migrate:install');
            $this->advanceBar();

            $this->info('Running migrations...');
            $this->call('migrate');
            $this->advanceBar();

            $this->info('Running composer...');
            //shell_exec('composer install');
            $this->advanceBar();

            $this->info('Generating supervisor configuration file...');
            $this->setupSupervisord();
            $this->advanceBar();

            if ($this->confirm('Do you wish run the queue with cron?')) {
                $this->setupCronTab();
                $this->advanceBar();
            }


            $this->bar->finish();
            $this->info(' '. "\n");
            $this->info('Done...');
            DB::commit();
        } catch (\Exception $e) {
            $this->info(' '. "\n");
            $this->error('Erro:' . $e->getMessage());
            DB::rollBack();
        }
    }

    private function createDatabase()
    {
        DB::statement(
            'CREATE DATABASE IF NOT EXISTS ' . env('DB_DATABASE', 'task_control')
        );
    }

    private function setupSupervisord()
    {
        exec("rm -f {$this->baseDir}/task_control.conf");
        exec("touch {$this->baseDir}/task_control.conf");      

        $config = "
[program:task-control-queue-listen]
command={$this->phpPath} {$this->baseDir}/artisan queue:listen --sleep=3 --tries=3
user={$this->userName}
process_name=%(program_name)s_%(process_num)d
directory={$this->baseDir}
stdout_logfile={$this->baseDir}/storage/logs/supervisor.log
redirect_stderr=true
autostart=true
autorestart=true
startretries=3
numprocs=2";

        exec("sh -c 'echo \"$config\" >> {$this->baseDir}/task_control.conf'");
    }

    private function setupCronTab()
    {
        $this->info('Creating cron...');
        $line = "* * * * * {$this->phpPath} {$this->baseDir}/artisan schedule:run 1>> /dev/null 2>&1";
        $this->info($line);
        exec('echo crontab -l | { cat; echo "'.$line.'"; } | crontab -');
    }

    private function advanceBar()
    {
        $this->bar->advance();
        $this->info(' '. "\n");
    }
}
