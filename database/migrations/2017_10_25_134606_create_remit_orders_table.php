<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemitOrdersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('remit_orders', function(Blueprint $table) {
            $table->increments('id');
            $table->string('plat_no', 32)->comment('平台订单号');
            $table->string('batch_no', 32)->comment('订单批次号');
            $table->integer('uid')->comment('用户id');
            $table->decimal('money', 12, 2)->comment('提现金额');
            $table->decimal('fee', 8, 2)->comment('手续费');
            $table->decimal('ac_money', 12, 2)->comment('实际金额');
            $table->tinyInteger('classify')->default(0)->comment('结算类别 0提现 1代付');
            $table->tinyInteger('status')->default(0)->comment('单据状态 0待打款/审核 1打款中 2打款成功 3已撤销 4已退回');
            $table->tinyInteger('disposal')->default(0)->comment('处理方式 0人工处理 1api处理');
            $table->string('bk_username', 150)->comment('开户人名称');
            $table->string('bk_category', 15)->comment('银行卡类别');
            $table->string('bk_account', 30)->comment('银行账号');
            $table->string('bk_prov', 150)->comment('银行开户省份');
            $table->string('bk_city', 100)->comment('银行开户城市');
            $table->string('bk_branch', 255)->comment('银行开户分行');
            $table->string('bk_number', 50)->nullable()->comment('联行号');

            $table->integer('adminid')->nullable()->comment('操作人员id');
            $table->timestamp('operated_at')->nullable()->comment('操作时间');
            $table->softDeletes();
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
		Schema::drop('remit_orders');
	}

}
