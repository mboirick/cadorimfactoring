<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaiementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paiements', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('id_commande');
			$table->string('payment_id');
			$table->string('payment_type');
			$table->text('payment_status', 65535);
			$table->text('payment_amount', 65535);
			$table->text('payment_currency', 65535);
			$table->string('payment_date');
			$table->text('payer_email', 65535)->nullable();
			$table->string('address_name');
			$table->string('somme_mru', 50)->default('0');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('paiements');
	}

}
