<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSystemPermissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('system_permission', function(Blueprint $table)
		{
			$table->foreign('action_id', 'FK_perm_idAcao')->references('id')->on('system_module_action')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('profile_id', 'FK_perm_idPerfil')->references('id')->on('system_profile')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('system_permission', function(Blueprint $table)
		{
			$table->dropForeign('FK_perm_idAcao');
			$table->dropForeign('FK_perm_idPerfil');
		});
	}

}
