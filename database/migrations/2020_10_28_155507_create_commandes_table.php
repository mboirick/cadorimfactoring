<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommandesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commandes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_commande');
			$table->string('mail_commande', 25);
			$table->string('nom_produit');
			$table->string('prix_produit');
			$table->integer('quantite');
			$table->dateTime('date_commande')->nullable();
			$table->dateTime('date_validation')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('commandes');
	}

}
