<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatUserSettleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plat_user_settle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户id');
            $table->tinyInteger('classify')->comment('0个人账户 1企业账户');
            $table->string('bank_no')->comment('开户账号');
            $table->integer('bank_id')->comment('开户银行');
            $table->string('bank_code')->comment('开户账户');
            $table->string('city_id')->comment('开户银行所在城市id');
            $table->string('bank_branch')->comment('开户分行');
            $table->string('full_addr')->comment('开户行全名');
            $table->string('ali_name')->nullable()->comment('支付宝名称');
            $table->string('alipay')->nullable()->comment('支付宝账户');
            $table->tinyInteger('is_default')->default(0)->comment('是否默认');
            $table->unique('uid');
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
        Schema::dropIfExists('plat_user_settle');
    }
}
