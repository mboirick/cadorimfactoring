<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProduitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('produits', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('nom_produit');
			$table->text('desc_produit', 65535);
			$table->string('img_produit');
			$table->string('alt_img');
			$table->float('prix_produit', 10, 0);
			$table->float('prix_promo', 10, 0)->nullable();
			$table->float('frais_trans', 10, 0);
			$table->string('delai_trans')->default('2 Minutes');
			$table->boolean('is_actif')->default(0);
			$table->string('is_actif_cat')->default('0');
			$table->string('categ_produit');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('produits');
	}

}
