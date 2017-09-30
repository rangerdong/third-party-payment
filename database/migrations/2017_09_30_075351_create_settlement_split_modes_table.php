<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlementSplitModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_split_modes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('定义模式名');
            $table->integer('pm_id')->comment('0全部银行');
            $table->integer('df_if_id')->comment('默认接口');
            $table->integer('sp_if_id')->default(0)->comment('备用接口');
            $table->tinyInteger('is_default')->default(0)->comment('是否默认 0否 1默认');
            $table->tinyInteger('settle_cycle')->default(0)->comment('结算周期 t+');
            $table->unique(['name', 'pm_id']);
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
        Schema::dropIfExists('settlement_split_modes');
    }
}
