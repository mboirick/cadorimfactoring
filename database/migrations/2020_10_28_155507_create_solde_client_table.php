<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSoldeClientTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('solde_client', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_client', 50);
			$table->string('id_client_debiteur', 50);
			$table->float('solde_avant_euros', 10, 0)->nullable();
			$table->float('solde_avant_mru', 10, 0)->nullable();
			$table->float('solde_euros', 10, 0)->nullable();
			$table->float('solde_mru', 10, 0)->nullable();
			$table->float('montant_euros', 10, 0)->nullable();
			$table->float('taux', 10, 0)->nullable();
			$table->float('montant_mru', 10, 0)->nullable();
			$table->timestamps();
			$table->integer('indice')->default(1);
			$table->string('motif')->nullable();
			$table->string('type_opperation', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('solde_client');
	}

}
