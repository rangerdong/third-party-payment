<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargeSplitModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_split_modes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('定义模式名');
            $table->integer('pm_id')->comment('通道类型');
            $table->integer('df_if_id')->comment('默认接口');
            $table->integer('sp_if_id')->default(0)->comment('备用接口');
            $table->decimal('rate', 5,3)->default(99)->comment('默认费率');
            $table->tinyInteger('is_default')->default(0)->comment('是否默认 0否 1默认');
            $table->unique(['name', 'pm_id']);
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
        Schema::dropIfExists('recharge_split_modes');
    }
}
