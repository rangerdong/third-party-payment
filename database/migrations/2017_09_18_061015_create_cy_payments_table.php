<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cy_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identify', 50)->comment('通道标识');
            $table->tinyInteger('status')->default(1)->comment('通道状态 0关闭 1开启');
            $table->tinyInteger('order')->default(0)->comment('排序值');
            $table->decimal('rate', 5, 3)->default('98.00')->comment('通道费率');
            $table->integer('split_mode')->default(0)->comment('处理模式 0选择默认的处理模式走');
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
        Schema::dropIfExists('cy_payments');
    }
}
