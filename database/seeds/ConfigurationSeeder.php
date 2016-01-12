<?php

use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configuration = new \App\Models\Configuration();
        $configuration->send_email_process = 1;
        $configuration->save();
    }
}
