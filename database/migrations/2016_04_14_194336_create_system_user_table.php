<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_user', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('profile_id')->unsigned()->index('FK_usr_idPerfil');
			$table->dateTime('last_access')->nullable();
			$table->string('name', 150);
			$table->string('email', 150)->unique('UQ_usr_email');
			$table->string('password', 100);
			$table->string('image', 150);
			$table->enum('status', array('active','inactive'))->default('active');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('system_user');
	}

}
