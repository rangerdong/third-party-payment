<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dict_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('通道名称');
            $table->string('identify', 50)->comment('通道编码');
            $table->tinyInteger('order')->default(0)->comment('排序ID');
            $table->tinyInteger('type')->default(0)->comment('0 一级通道 1 网银通道');
            $table->timestamps();
        });

        Schema::create('dict_interfaces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('接口商名称');
            $table->string('identify', 50)->comment('接口商编码');
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
        Schema::dropIfExists('dict_payments');
        Schema::dropIfExists('dict_interfaces');
    }
}
