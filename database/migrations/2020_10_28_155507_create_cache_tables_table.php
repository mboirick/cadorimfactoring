<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCacheTablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cache_tables', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('user_type', 10)->nullable();
			$table->string('id_operation', 13)->nullable();
			$table->string('id_client', 100)->nullable();
			$table->string('expediteur');
			$table->string('nom_benef', 50);
			$table->string('phone_benef');
			$table->string('montant_euro', 100)->default('Null');
			$table->string('montant', 50);
			$table->string('solde_avant', 50);
			$table->string('solde_apres', 50);
			$table->string('solde', 50)->default('0');
			$table->string('code_confirmation', 50)->nullable();
			$table->string('operation', 20);
			$table->boolean('statut')->nullable()->default(0);
            $table->text('invoices')->default('Null');
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
		Schema::drop('cache_tables');
	}

}
