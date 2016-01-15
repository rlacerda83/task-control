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
    protected $signature = 'install {--sed} {--no-db}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup application';

    /**
     * @var string
     */
    protected $phpPath;

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $userName;

    /**
     * @var mixed
     */
    protected $serverName;

    /**
     * @var
     */
    protected $bar;

    public function __construct()
    {
        parent::__construct();
        $this->phpPath = exec('which php');
        $this->baseDir = str_replace('/app', '', app_path());
        $this->userName = exec('whoami');
        $this->serverName = env('APP_SERVER_NAME', 'taskcontrol.localhost');
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

            $this->bar = $this->output->createProgressBar($this->getTotalBar());
            $this->bar->setFormat('debug');
            $this->info(' ');

            $this->call('key:generate');

            if (!$this->option('sed')) {
                if (!$this->option('no-db')) {

                    $this->info('Installing migrations...');
                    $this->call('migrate:install');
                    $this->advanceBar();

                    $this->info('Running migrations...');
                    $this->call('migrate:refresh');
                    $this->advanceBar();
                }
            }

            $this->info('Generating supervisor configuration file...');
            $this->setupSupervisord();
            $this->advanceBar();

            $this->info('Generating cron line configuration ...');
            $this->setupCronTab();
            $this->advanceBar();

            $this->info('Generating nginx configuration ...');
            $this->setupNginx();

            $this->bar->finish();
            $this->info(' '. "\n");
            $this->info('Done. Do not forget to configure your web server.');
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
        exec("rm -f {$this->baseDir}/supervisor");
        exec("sed -e 's|#php_path|{$this->phpPath}|' -e 's|#base_dir|{$this->baseDir}|' -e 's|#user_name|{$this->userName}|' $this->baseDir/supervisor.template > $this->baseDir/supervisor");
    }

    private function setupCronTab()
    {
        exec("rm -f {$this->baseDir}/cron");
        exec("sed -e 's|#php_path|{$this->phpPath}|' -e 's|#base_dir|{$this->baseDir}|' $this->baseDir/cron.template > $this->baseDir/cron");
    }

    private function setupNginx()
    {
        exec("rm -f {$this->baseDir}/cron");
        exec("sed -e 's|#server_name|{$this->serverName}|' -e 's|#base_dir|{$this->baseDir}|' $this->baseDir/nginx.template > $this->baseDir/nginx");
    }

    private function advanceBar()
    {
        $this->bar->advance();
        $this->info(' '. "\n");
    }

    private function getTotalBar()
    {
        $total = 5;
        if ($this->option('sed')) {
            return 3;
        }

        if ($this->option('no-db')) {
            return 3;
        }

        return $total;
    }
}
