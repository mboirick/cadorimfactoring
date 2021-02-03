<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clients', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('societe', 50);
			$table->string('nom_prenom', 100)->default('');
			$table->string('adresse', 80)->default('');
			$table->string('telephone', 20)->default('');
			$table->string('remarque')->default('');
			$table->float('cash_out', 10, 0)->default(0);
			$table->float('cash_in', 10, 0)->default(0);
			$table->float('solde', 10, 0)->default(0);
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
		Schema::drop('clients');
	}

}
