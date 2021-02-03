<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatAtlpayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stat_atlpay', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->dateTime('date')->nullable()->unique('date');
			$table->integer('nbr_transaction');
			$table->float('somme_brut', 10, 0);
			$table->float('frais_atl', 10, 0);
			$table->float('somme_net', 10, 0);
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
		Schema::drop('stat_atlpay');
	}

}
