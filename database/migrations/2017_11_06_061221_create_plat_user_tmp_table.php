<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatUserTmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plat_user_tmp', function (Blueprint $table) {
            $table->string('username', 150)->comment('注册邮件用户名');
            $table->string('phone')->comment('注册手机号');
            $table->string('token', 150)->comment('注册token');
            $table->integer('expired_at')->comment('过期时间');
            $table->primary('username');
            $table->index('token');
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
        Schema::dropIfExists('plat_user_tmp');
    }
}
