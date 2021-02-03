<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAtlpayTbTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('atlpay_tb', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('last4_digits', 5)->default('');
			$table->string('masked_card', 20)->default('');
			$table->string('card_type', 30)->default('');
			$table->string('card_brand', 10)->default('');
			$table->string('status', 20)->default('');
			$table->string('commission', 10)->default('');
			$table->string('description', 100)->default('');
			$table->string('type', 20)->default('');
			$table->string('success_return', 100)->default('');
			$table->string('cancel_return', 100)->default('');
			$table->string('failure_return', 100)->default('');
			$table->string('name', 20)->default('');
			$table->string('email', 20)->default('');
			$table->string('ip_address', 20)->default('');
			$table->string('card_country_code', 5)->default('');
			$table->string('currency_code', 5)->default('');
			$table->string('txn_reference', 20)->default('');
			$table->string('atlpay_txn_id', 20)->default('');
			$table->string('billing_address_1', 100)->default('');
			$table->string('billing_address_2', 100)->default('');
			$table->string('billing_city', 50)->default('');
			$table->string('billing_state', 20)->default('');
			$table->string('billing_postal_code', 10)->default('');
			$table->string('billing_country_code', 5)->default('');
			$table->string('gross_amount', 10)->default('');
			$table->string('net_amount', 10)->default('');
			$table->string('merchant', 10)->default('');
			$table->string('testMode', 5)->default('');
			$table->timestamp('creat_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('atlpay_tb');
	}

}
