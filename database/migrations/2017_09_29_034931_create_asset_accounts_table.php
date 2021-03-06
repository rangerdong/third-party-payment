<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户id');
            $table->decimal('available', 16, 6)->default(0)->unsigned()->comment('可用余额');
            $table->decimal('recharge_frozen', 16, 6)->default(0)->unsigned()->comment('交易冻结金额');
            $table->decimal('withdraw_frozen', 16, 6)->default(0)->unsigned()->comment('提现冻结金额');
            $table->decimal('other_frozen', 16, 6)->default(0)->unsigned()->comment('其余冻结／手动冻结');
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
        Schema::dropIfExists('asset_accounts');
    }
}
