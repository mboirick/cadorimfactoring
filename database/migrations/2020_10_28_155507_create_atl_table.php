<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAtlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('atl', function(Blueprint $table)
		{
			$table->string('A', 14)->nullable();
			$table->bigInteger('B')->nullable();
			$table->string('C', 10)->nullable();
			$table->string('D', 9)->nullable();
			$table->string('E', 10)->nullable();
			$table->string('F', 6)->nullable();
			$table->string('G', 39)->nullable();
			$table->string('H', 25)->nullable();
			$table->string('I', 159)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('atl');
	}

}
