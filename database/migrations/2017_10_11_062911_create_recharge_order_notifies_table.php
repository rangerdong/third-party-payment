<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargeOrderNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_order_notifies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->comment('订单id');
            $table->tinyInteger('notify_time')->default(0)->comment('通知次数');
            $table->string('notify_url', '500')->comment('通知url');
            $table->tinyInteger('res_status')->nullable()->comment('响应码');
            $table->text('res_content')->nullable()->comment('响应内容');
            $table->tinyInteger('status')->default(0)->comment('0失败 1成功');
            $table->timestamp('notified_at')->nullable()->comment('上次通知时间');
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
        Schema::dropIfExists('recharge_order_notifies');
    }
}
