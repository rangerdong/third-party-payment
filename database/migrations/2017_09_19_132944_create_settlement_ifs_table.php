<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlementIfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_ifs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('接口名称');
            $table->string('identify', 20)->comment('接口商标识');
//            $table->string('gw_pay')->comment('结算网关');
//            $table->string('gw_query')->comment('结算查询网关');
            $table->string('mc_id', 255)->comment('商户id');
            $table->string('mc_key', 255)->comment('商户密钥');
            $table->tinyInteger('type')->default(1)->comment('结算类型 1单笔结算 2批量结算');
            $table->tinyInteger('status')->default(1)->comment('渠道状态 0关闭 1开启');
            $table->string('ext', 500)->nullable()->comment('额外字段');
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
        Schema::dropIfExists('settlement_ifs');
    }
}
