<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdresseAgencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adresse_agences', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_agence', 20);
			$table->string('ville', 20);
			$table->string('quartier', 100);
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
		Schema::drop('adresse_agences');
	}

}
