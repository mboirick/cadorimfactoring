<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDivisionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('division', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 60);
			$table->string('taux', 10);
			$table->string('somme_min')->default('0');
			$table->string('somme_max')->default('0');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('division');
	}

}
