<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlementGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('分组名称');
            $table->tinyInteger('classify')->comment('分组类别 0商户 1代理 2商务');
            $table->decimal('single_min', 14, 4)->default(1)->comment('平台单笔最低金额');
            $table->decimal('single_max', 14, 4)->default(100)->comment('平台单笔最大金额');
            $table->decimal('daily_total', 14, 4)->default(100)->comment('平台当日提款总数');
            $table->integer('daily_max')->default(10)->comment('平台当日最大提款数');
            $table->tinyInteger('fee_type')->default(0)->comment('平台手续费类型 0固定 1按比例');
            $table->decimal('fee_fixed', 5, 2)->nullable()->default(1)->comment('平台固定手续费');
            $table->decimal('fee_rate', 4, 2)->nullable()->default(2)->comment('平台手续费比例');
            $table->decimal('fee_max', 7, 2)->nullable()->default(5)->comment('最高手续费 类型为按比例时生效');
            $table->decimal('fee_min', 5, 2)->nullable()->default(1)->comment('最低手续费 类型为按比例时生效');
            $table->decimal('single_min_api', 14, 4)->default(1)->comment('api单笔最低金额');
            $table->decimal('single_max_api', 14, 4)->default(100)->comment('api单笔最高金额');
            $table->decimal('daily_total_api', 14, 4)->default(100)->comment('api当日提款总数');
            $table->integer('daily_max_api')->default(10)->comment('当日api最大提款数');
            $table->integer('daily_api_call')->default(100)->comment('api当日最大调用次数');
            $table->tinyInteger('fee_type_api')->default(0)->comment('api手续费类型 0固定 1按比例');
            $table->decimal('fee_fixed_api', 5, 2)->default(1)->comment('固定手续费');
            $table->decimal('fee_rate_api', 4, 2)->nullable()->default(2)->comment('手续费比例');
            $table->decimal('fee_max_api', 7, 2)->nullable()->default(5)->comment('最高手续费 类型为按比例时生效');
            $table->decimal('fee_min_api', 5, 2)->nullable()->default(1)->comment('最低手续费 类型为按比例时生效');
            $table->tinyInteger('is_default')->default(0)->comment('是否默认');
            $table->unique(['classify', 'name']);
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
        Schema::dropIfExists('settlement_groups');
    }
}
