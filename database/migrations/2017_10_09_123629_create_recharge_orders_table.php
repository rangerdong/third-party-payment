<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plat_no', 32)->comment('平台订单号');
            $table->string('merchant_no', 32)->comment('商家订单号');
            $table->string('third_no', 50)->nullable()->comment('第三方订单号');
            $table->integer('uid')->comment('用户id');
            $table->integer('app_id')->comment('appid');
            $table->integer('pm_id')->comment('通道id');
            $table->decimal('order_amt', 12, 2)->comment('订单金额');
            $table->decimal('order_fee', 9, 5)->comment('订单手续费');
            $table->decimal('order_settle', 12, 5)->comment('实际结算');
            $table->decimal('user_rate', 5, 3)->default(0)->comment('用户费率');
            $table->integer('proxy')->default(0)->comment('代理id');
            $table->integer('business')->default(0)->comment('商务id');
            $table->decimal('proxy_rate', 5, 3)->default(0)->comment('代理费率');
            $table->decimal('business_rate', 5, 3)->default(0)->comment('商务费率');
            $table->tinyInteger('order_status')->default(0)->comment('订单状态 0 待支付 1付款成功 -1付款失败');
            $table->integer('upper')->comment('第三方厂商');
            $table->decimal('upper_rate', 5, 3)->default(0)->comment('第三方费率');
            $table->json('order_data')->comment('充值订单json');
            $table->json('third_notify')->nullable()->comment('第三方通知数据');
            $table->tinyInteger('third_notify_time')->default(0)->comment('第三方通知次数');
            $table->tinyInteger('order_notify_time')->default(0)->comment('订单通知次数');
            $table->integer('req_ip')->unsigned()->default(0)->comment('请求ip');
            $table->tinyInteger('settle_day')->unsigned()->default(0)->comment('结算周期');
            $table->tinyInteger('is_settle')->unsigned()->default(0)->comment('是否结算 0未结算1已结算');
            $table->timestamp('settled_at')->nullable()->comment('结算时间');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['uid', 'app_id', 'merchant_no']);
            $table->unique('order_no');
            $table->unique('third_no');
            $table->unique('merchant_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recharge_orders');
    }
}
