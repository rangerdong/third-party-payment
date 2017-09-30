<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvinceCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('province_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 500)->comment('名称');
            $table->string('index')->comment('序号');
            $table->integer('parent')->default(0)->comment('上级id');
            $table->tinyInteger('depth')->comment('层级');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('province_cities');
    }
}
