<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemitOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remit_order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->comment('出款订单id');
            $table->integer('if_id')->nullable()->comment('接口id');
            $table->string('if_identify')->comment('接口标识');
            $table->json('post_data')->comment('接口传递json');
            $table->tinyInteger('if_status')->default(0)->comment('0提交成功 1提交失败 2打款成功 3接口打款失败');
            $table->string('if_return', 500)->nullable()->comment('接口返回数据');
            $table->json('if_notify')->nullable()->comment('接口通知数据');
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
        Schema::dropIfExists('remit_order_details');
    }
}
