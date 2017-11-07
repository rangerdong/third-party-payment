<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCacheMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cache_mobiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile', 11)->comment('手机号');
            $table->tinyInteger('is_verified')->default(0)->comment('是否验证');

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
        Schema::dropIfExists('cache_mobiles');
    }
}
