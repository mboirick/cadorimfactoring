<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBilldepositwithdrawalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('billdepositwithdrawal', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_client', 50);
			$table->string('id_user_debtor', 50);
			$table->float('amount', 10, 0)->default(0);
			$table->timestamps();
			$table->string('reason')->nullable();
			$table->string('type_operation', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('billdepositwithdrawal');
	}

}
