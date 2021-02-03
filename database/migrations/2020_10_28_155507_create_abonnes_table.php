<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAbonnesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('abonnes', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('genre', 10)->default('homme');
			$table->date('reset_at')->nullable()->index('username_key');
			$table->string('password')->default('');
			$table->string('confirmation_token')->nullable();
			$table->string('prenom', 50)->default('')->index('prenom');
			$table->string('email', 100)->default('')->index('email');
			$table->string('adress', 100)->default('');
			$table->string('ville', 20)->default('');
			$table->string('code_postal', 10)->default('');
			$table->string('pays_residence', 20)->default('');
			$table->date('date_naissance')->nullable();
			$table->string('type_doc', 20)->default('');
			$table->string('numero_doc', 20)->default('');
			$table->date('date_emission')->nullable();
			$table->date('date_expiration')->nullable();
			$table->string('document', 150)->default('');
			$table->timestamp('confirmed_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('reset_token')->default('');
			$table->string('phone', 50);
			$table->string('username', 250)->default('');
			$table->integer('kyc')->default(0);
			$table->timestamps();
			$table->string('unique_id', 13)->nullable();
			$table->integer('id_parrain')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('abonnes');
	}

}
