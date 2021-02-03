<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUpdateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('update_users', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('up_nom');
			$table->string('up_prenom');
			$table->string('up_adress');
			$table->string('up_phone', 25);
			$table->string('up_email', 25);
			$table->string('up_password');
			$table->string('up_token');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('update_users');
	}

}
