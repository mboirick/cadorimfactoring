<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatAgencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stat_agences', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_agence', 100);
			$table->string('email_agence', 100);
			$table->date('jours')->nullable();
			$table->integer('nbr_operation');
			$table->string('total', 100);
			$table->string('total_euro', 100)->nullable();
			$table->string('total_gaza', 100)->nullable();
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
		Schema::drop('stat_agences');
	}

}
