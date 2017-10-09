<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlementPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('dict_id')->comment('通道字典id');
            $table->tinyInteger('support')->default(0)->comment('0平台提现 1api结算 2均支持');
            $table->integer('mode_id')->default(0)->comment('通道处理模式id');
            $table->unique('dict_id');
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
        Schema::dropIfExists('settlement_payments');
    }
}
