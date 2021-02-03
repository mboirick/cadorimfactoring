<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSondagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sondages', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_sondage', 50);
			$table->string('id_question', 10);
			$table->string('text_question');
			$table->string('type_question');
			$table->string('id_reponse', 10);
			$table->string('reponse')->nullable();
			$table->string('precision')->nullable();
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
		Schema::drop('sondages');
	}

}
