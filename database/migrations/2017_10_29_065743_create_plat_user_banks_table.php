<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatUserBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plat_user_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户id');
            $table->tinyInteger('classify')->comment('0个人账户 1企业账户');
            $table->string('username', 150)->comment('开户人名称');
            $table->string('category', 20)->comment('开户银行类型');
            $table->string('account', 30)->comment('开户账户号');
            $table->integer('city_id')->comment('开户银行所在城市id');
            $table->string('branch')->comment('开户分行');
            $table->string('number')->default('')->nullable()->comment('联行号');
            $table->tinyInteger('is_default')->default(0)->comment('是否默认');
            $table->unique('account');
            $table->index(['username', 'account', 'category']);
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
        Schema::dropIfExists('plat_user_banks');
    }
}
