<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawOrdersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('withdraw_orders', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户id');
            $table->decimal('order_amt', 12, 2)->comment('提现金额');

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
		Schema::drop('withdraw_orders');
	}

}
