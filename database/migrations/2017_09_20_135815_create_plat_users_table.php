<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plat_users', function (Blueprint $table) {
            $table->increments('id');
            //基本信息
            $table->string('username', 100)->comment('用户名');
            $table->string('password')->comment('登录密码');
            $table->string('email')->nullable()->comment('邮箱号');
            $table->string('phone', 11)->nullable()->comment('手机号');
            $table->tinyInteger('type')->comment('账户类型 0个人 1企业');
            $table->tinyInteger('status')->default(0)->comment('账户状态 -1停用 0未审核 1已审核');
            $table->tinyInteger('audit_realname')->default(0)->comment('实名认证0未认证 1已认证');
            $table->tinyInteger('audit_company')->default(0)->comment('企业认证0未认证 1已认证');
            $table->tinyInteger('audit_domains')->default(0)->comment('网址域名验证 0未验证 1已验证');
            $table->integer('audit_id')->default(0)->comment('认证资料信息');
            //登录信息
            $table->timestamp('last_at')->comment('上次登录时间');
            $table->integer('last_ip')->unsigned()->comment('上次登录IP');
            $table->integer('reg_ip')->unsigned()->comment('注册ip');

            //账户角色信息
            $table->string('code', 10)->comment('账户编码');
            $table->tinyInteger('role')->comment('角色类型 0商户 1代理 2商务');
            $table->integer('upper_id')->default(0)->comment('上级id 0无上级');
            $table->string('trade_pwd')->comment('交易密码');
            $table->tinyInteger('is_withdraw')->default(0)->comment('是否允许提现 0不允许 1允许');
            $table->tinyInteger('settle_type')->default(0)->comment('结算类型 0 平台结算 1 API结算');
            $table->tinyInteger('recharge_api')->default(0)->comment('支付api功能 0未开通 1开通');
            $table->tinyInteger('settle_api')->default(0)->comment('结算api功能 0未开通 1开通');
            $table->tinyInteger('group_id')->comment('分组id');


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
        Schema::dropIfExists('plat_users');
    }
}
