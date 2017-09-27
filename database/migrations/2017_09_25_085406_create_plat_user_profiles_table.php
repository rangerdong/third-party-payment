<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plat_user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('商户id');
            $table->tinyInteger('property')->comment('认证性质 0个人 1企业');
            $table->tinyInteger('role')->comment('认证类型0商户 1代理');
            $table->string('realname', 100)->comment('真实姓名');
            $table->string('idcard', 18)->comment('身份证号码');
            $table->integer('scope')->comment('经营范围id');
            $table->string('enterprise', 255)->nullable()->comment('企业名称');
            $table->integer('city_id')->comment('城市id');
            $table->string('address', 255)->comment('详细地址');
            $table->string('full_addr', 500)->comment('地址全名');
            $table->string('license', 50)->nullable()->comment('营业执照号');
            $table->string('img_id_hand')->comment('手持证件照');
            $table->string('img_id_front')->comment('正面证件照');
            $table->string('img_id_back')->comment('背面证件照');
            $table->string('img_license')->nullable()->comment('营业执照照片');
            $table->string('img_tax')->nullable()->comment('税务许可证');
            $table->string('img_permit')->nullable()->comment('文网文辅助文件');
            $table->string('remark', 500)->nullable()->comment('备注信息');
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
        Schema::dropIfExists('plat_user_profiles');
    }
}
