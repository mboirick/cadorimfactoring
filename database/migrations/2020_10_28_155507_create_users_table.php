<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('id_client', 50)->nullable();
			$table->string('user_type', 11)->nullable();
			$table->string('name', 50)->nullable();
			$table->string('username', 191)->nullable();
			$table->string('email', 191)->unique();
			$table->string('phone', 20)->nullable();
			$table->string('password', 191);
			$table->string('lastname', 191);
			$table->string('firstname', 191);
			$table->string('remember_token', 100)->nullable();
			$table->softDeletes();
			$table->timestamps();
			$table->dateTime('email_verified_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
