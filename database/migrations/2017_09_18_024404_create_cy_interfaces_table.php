<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyInterfacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('cy_interfaces')) {
            Schema::create('cy_interfaces', function (Blueprint $table) {
                $table->increments('id');
                $table->string('identify', 50)->comment('渠道商标识');
                $table->string('name', 100)->comment('自定义名称');
                $table->string('gw_pay', 255)->comment('支付网关');
                $table->string('gw_query', 255)->comment('支付查询网关');
                $table->string('gw_refund', 255)->nullable()->comment('退款网关');
                $table->string('gw_refund_query', 255)->nullable()->comment('退款查询网关');
                $table->string('mc_id', 255)->comment('商户id');
                $table->string('mc_key', 255)->comment('商户密钥');
                $table->tinyInteger('status')->default(1)->comment('渠道商状态, 0关闭 1开启');
                $table->string('ext', 500)->nullable()->comment('额外字段');
                $table->softDeletes();
                $table->timestamps();

            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('cy_interfaces');
    }
}
