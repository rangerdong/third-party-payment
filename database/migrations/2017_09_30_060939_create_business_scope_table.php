<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessScopeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_scope', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 500)->comment('范围名称');
            $table->integer('parent')->comment('父级id');
            $table->integer('index')->default(0)->comment('排序id');
            $table->tinyInteger('depth')->default(0)->comment('层级');
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
        Schema::dropIfExists('business_scope');
    }
}
