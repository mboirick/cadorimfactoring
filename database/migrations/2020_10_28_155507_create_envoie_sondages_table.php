<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEnvoieSondagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('envoie_sondages', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('email', 100);
			$table->string('id_sondages', 50);
			$table->integer('repondu')->default(0);
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
		Schema::drop('envoie_sondages');
	}

}
