<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReponseSondagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reponse_sondages', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_sondage', 50);
			$table->string('id_client', 100);
			$table->string('id_question', 10);
			$table->string('text_question', 100);
			$table->string('response');
			$table->boolean('repondu')->default(0);
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
		Schema::drop('reponse_sondages');
	}

}
