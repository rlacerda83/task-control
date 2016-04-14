<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSystemUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('system_user', function(Blueprint $table)
		{
			$table->foreign('profile_id', 'FK_usr_idPerfil')->references('id')->on('system_profile')->onUpdate('NO ACTION')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('system_user', function(Blueprint $table)
		{
			$table->dropForeign('FK_usr_idPerfil');
		});
	}

}
