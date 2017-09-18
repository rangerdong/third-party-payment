<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCySplitModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cy_split_modes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('处理模式定义名');
            $table->integer('default')->comment('默认渠道');
            $table->integer('spare')->comment('备用渠道');
            $table->string('type')->comment('通道类型');
            $table->tinyInteger('is_default')->default(0)->comment('是否默认 0否 1默认');
            $table->tinyInteger('is_single')->default(0)->comment('每日限领');
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
        Schema::dropIfExists('cy_split_modes');
    }
}
