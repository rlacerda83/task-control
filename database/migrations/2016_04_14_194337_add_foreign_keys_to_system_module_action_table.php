<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSystemModuleActionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('system_module_action', function(Blueprint $table)
		{
			$table->foreign('module_id', 'FK_mod_acao_idModulo')->references('id')->on('system_module')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('system_module_action', function(Blueprint $table)
		{
			$table->dropForeign('FK_mod_acao_idModulo');
		});
	}

}
