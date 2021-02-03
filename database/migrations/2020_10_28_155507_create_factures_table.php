<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFacturesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('factures', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('type', 10)->default('emis');
			$table->string('id_client', 110);
			$table->string('id_paiement', 20);
			$table->string('numero_facture')->nullable();
			$table->string('path')->nullable();
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
		Schema::drop('factures');
	}

}
