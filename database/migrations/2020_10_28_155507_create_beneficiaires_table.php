<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBeneficiairesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('beneficiaires', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_client', 20);
			$table->string('entreprise', 50)->nullable();
			$table->string('nom', 50)->nullable();
			$table->string('prenom', 50)->nullable();
			$table->string('email', 100)->nullable();
			$table->string('phone', 20);
			$table->string('adresse', 100);
			$table->string('iban', 100)->nullable();
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
		Schema::drop('beneficiaires');
	}

}
