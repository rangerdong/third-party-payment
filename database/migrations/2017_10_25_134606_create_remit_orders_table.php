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
            $table->tinyInteger('status')->default(0)->comment('单据状态 0待审核 1待打款 2打款中 3打款成功 4已撤销 5已退回 6审核未通过');
            $table->tinyInteger('disposal')->default(0)->comment('处理方式 0人工处理 1api处理');
            $table->string('bk_username', 150)->comment('开户人名称');
            $table->string('bk_category', 15)->comment('银行卡类别');
            $table->string('bk_account', 30)->comment('银行账号');
            $table->string('bk_prov', 150)->comment('银行开户省份');
            $table->string('bk_city', 100)->comment('银行开户城市');
            $table->string('bk_branch', 255)->comment('银行开户分行');
            $table->string('bk_number', 50)->nullable()->comment('联行号');

            $table->string('remark', 500)->nullable()->comment('备注信息');
            $table->string('ad_remark', 500)->nullable()->comment('管理人员备注信息');
            $table->integer('adminid')->nullable()->comment('操作人员id');
            $table->timestamp('operated_at')->nullable()->comment('操作时间');
            $table->softDeletes();
            $table->timestamps();
            $table->unique('plat_no');
            $table->index('uid');
            $table->index('status');
            $table->index(['bk_category', 'bk_account']);
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
