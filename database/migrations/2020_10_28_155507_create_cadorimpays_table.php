<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCadorimpaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cadorimpays', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_paiement', 20);
			$table->string('id_client', 100);
			$table->string('entreprise', 50)->nullable();
			$table->string('adresse', 100)->nullable();
			$table->float('montant_euros', 10, 0);
			$table->float('taux_echange', 10, 0);
			$table->float('montant_mru', 10, 0);
			$table->string('iban', 100)->nullable();
			$table->string('remarque')->nullable();
			$table->string('reponses')->nullable();
			$table->integer('statut')->default(0);
			$table->string('type_demande', 10)->nullable();
			$table->date('date_limit')->nullable();
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
		Schema::drop('cadorimpays');
	}

}
