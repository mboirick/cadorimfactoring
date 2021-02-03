<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogSendmailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('log_sendmail', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('email')->nullable();
			$table->string('subject')->nullable();
			$table->date('datesend');
			$table->boolean('state');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('log_sendmail');
	}

}
