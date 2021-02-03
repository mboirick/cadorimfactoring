<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email', 191)->nullable();
			$table->string('coupon_code', 13)->unique('coupon_code');
			$table->integer('amount');
			$table->string('amount_type', 20);
			$table->date('expiry_date');
			$table->boolean('status');
			$table->integer('used')->default(0);
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
		Schema::drop('coupons');
	}

}
