<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('分组名称');
            $table->tinyInteger('classify')->comment('分组类别 0商户 1代理 2商务');
            $table->tinyInteger('is_default')->default(0)->comment('是否默认');
            $table->unique(['classify', 'name']);
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
        Schema::dropIfExists('recharge_groups');
    }
}
