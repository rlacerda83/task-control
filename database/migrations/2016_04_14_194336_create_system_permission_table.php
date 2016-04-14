<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemPermissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_permission', function(Blueprint $table)
		{
			$table->integer('profile_id')->unsigned()->index('FK_perm_idPerfil');
			$table->integer('action_id')->unsigned()->index('FK_perm_idAcao');
			$table->primary(['profile_id','action_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('system_permission');
	}

}
