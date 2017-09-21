<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargeGroupPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_group_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gid')->nullable()->comment('分组id');
            $table->integer('uid')->nullable()->comment('用户id');
            $table->integer('pm_id')->comment('通道id');
            $table->string('pm_identify', 50)->comment('通道编码');
            $table->decimal('rate', 5, 3)->comment('商户费率');
            $table->integer('mode_id')->comment('处理模式id');
            $table->tinyInteger('status')->default(1)->comment('是否开启');
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
        Schema::dropIfExists('recharge_group_payments');
    }
}
