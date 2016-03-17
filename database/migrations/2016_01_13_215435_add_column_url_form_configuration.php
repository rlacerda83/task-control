<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUrlFormConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hours_control', function ($table) {
            $table->tinyInteger('consolidate')->default(0);
            $table->date('base_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuration', function ($table) {
            $table->dropColumn(['consolidate']);
            $table->dropColumn(['base_date']);
        });
    }
}
