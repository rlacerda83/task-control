<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdUserTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function ($table) {
            $table->integer('user_id')->after('id')->unsigned();
            $table->foreign('user_id')->references('id')->on('system_user')->onDelete('cascade');
        });

        Schema::table('configuration', function ($table) {
            $table->integer('user_id')->after('id')->unsigned();
            $table->foreign('user_id')->references('id')->on('system_user')->onDelete('cascade');
        });

        Schema::table('hours_control', function ($table) {
            $table->integer('user_id')->after('id')->unsigned();
            $table->foreign('user_id')->references('id')->on('system_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function ($table) {
            $table->dropColumn('user_id');
            $table->dropForeign('tasks_user_id_foreign');
        });

        Schema::table('configuration', function ($table) {
            $table->dropColumn('user_id');
            $table->dropForeign('configuration_user_id_foreign');
        });

        Schema::table('hours_control', function ($table) {
            $table->dropColumn('user_id');
            $table->dropForeign('hours_control_user_id_foreign');
        });
    }
}
