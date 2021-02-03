<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoordonnesCommandesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coordonnes_commandes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_commande')->unique('id_commande');
			$table->string('mail_exp');
			$table->string('nom_exp');
			$table->string('phone_exp');
			$table->string('adress_exp');
			$table->string('nom_benef');
			$table->string('phone_benef');
			$table->string('adress_benef');
			$table->string('montant');
			$table->string('remise', 10)->default('0');
			$table->string('promo_code', 13)->nullable();
			$table->dateTime('date_commande')->nullable();
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('paiement_satus')->nullable();
			$table->string('tracker_status', 10)->nullable()->default('attente');
			$table->string('frais_gaza', 10)->default('0');
			$table->string('agence_gaza', 100)->default('');
			$table->string('point_retrait', 20)->nullable();
			$table->boolean('gaza_confirm')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('coordonnes_commandes');
	}

}
