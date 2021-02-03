<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskSendmailparrainageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_sendmailparrainage', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->boolean('sendall')->nullable();
			$table->date('datestart')->nullable();
			$table->date('dateend')->nullable();
			$table->integer('numbre')->nullable();
			$table->date('dateexcute')->nullable();
			$table->boolean('state')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_sendmailparrainage');
	}

}
