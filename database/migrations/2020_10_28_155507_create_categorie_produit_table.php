<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategorieProduitTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categorie_produit', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('nom');
			$table->string('description');
			$table->string('image');
			$table->string('alt_img');
			$table->boolean('is_valid')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categorie_produit');
	}

}
