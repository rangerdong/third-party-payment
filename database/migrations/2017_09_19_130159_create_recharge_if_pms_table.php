<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargeIfPmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_if_pms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('if_id')->comment('接口id');
            $table->integer('pm_id')->comment('通道id');
            $table->decimal('rate',5, 3)->default(98)->comment('接口支付通道费率');
            $table->unique(['if_id', 'pm_id']);
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
        Schema::dropIfExists('recharge_if_pms');
    }
}
