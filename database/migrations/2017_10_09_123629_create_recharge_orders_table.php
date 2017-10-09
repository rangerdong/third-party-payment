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
            $table->string('order_no', 32)->comment('平台订单号');
            $table->string('merchant_no', 32)->comment('商家订单号');
            $table->string('third_no', 50)->nullable()->comment('第三方订单号');
            $table->integer('uid')->comment('用户id');
            $table->integer('pm_id')->comment('通道id');
            $table->decimal('order_amt', 12, 2)->comment('平台订单号');
            $table->decimal('order_fee', 8, 4)->comment('订单手续费');
            $table->decimal('order_settle', 12, 4)->comment('实际结算');
            $table->integer('proxy')->default(0)->comment('代理id');
            $table->integer('business')->default(0)->comment('商务id');
            $table->decimal('proxy_fee', 8,4)->default(0)->comment('代理所得');
            $table->decimal('business_fee', 8,4)->default(0)->comment('商务所得');
            $table->tinyInteger('order_status')->default(0)->comment('订单状态 0 待支付 1付款成功 -1付款失败');
            $table->integer('upper')->comment('第三方厂商');
            $table->decimal('upper_fee', 8, 4)->default(0)->comment('第三方费率');
            $table->json('order_data')->comment('充值订单json');
            $table->json('third_notify')->nullable()->comment('第三方通知数据');
            $table->tinyInteger('third_notify_time')->default(0)->comment('第三方通知次数');
            $table->tinyInteger('order_notify_time')->default(0)->comment('订单通知次数');
            $table->softDeletes();
            $table->timestamps();
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
