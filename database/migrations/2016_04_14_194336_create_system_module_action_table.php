<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemModuleActionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_module_action', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('module_id')->unsigned()->index('FK_mod_acao_idModulo');
			$table->string('name', 60);
			$table->string('label', 60);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('system_module_action');
	}

}
