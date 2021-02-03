<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnalysesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('analyses', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('client', 100)->unique('client');
			$table->string('q1');
			$table->string('q2');
			$table->string('q3');
			$table->string('q4');
			$table->string('q5');
			$table->string('q6');
			$table->string('q7');
			$table->string('q8');
			$table->string('q9');
			$table->string('q10');
			$table->string('q11');
			$table->string('q12');
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
		Schema::drop('analyses');
	}

}
