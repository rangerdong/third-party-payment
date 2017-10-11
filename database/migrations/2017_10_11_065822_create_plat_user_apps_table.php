<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatUserAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plat_user_apps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户id');
            $table->string('app_id', 32)->comment('应用唯一id');
            $table->string('name', 150)->comment('应用名称');
            $table->tinyInteger('classify')->comment('应用类型 0 PC/WAP 1安卓 2ios');
            $table->string('domain', 50)->nullable()->comment('应用域名/安卓签名/iosbundleid');
            $table->string('icp', 50)->nullable()->comment('icp备案号/安卓包名');
            $table->tinyInteger('status')->default(0)->comment('应用状态 0 未审核 1已审核 2审核拒绝');
            $table->string('remark', 500)->nullable()->comment('备注信息');
            $table->string('notify_url', 200)->nullable()->comment('消息通知url');
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
        Schema::dropIfExists('plat_user_apps');
    }
}
