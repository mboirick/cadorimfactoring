<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTauxEchangeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('taux_echange', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('taux_euros', 11);
			$table->string('taux_dollar', 11);
			$table->timestamp('date_update')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('taux_echange');
	}

}
