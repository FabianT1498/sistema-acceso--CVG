<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_models', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name')->unique();
            $table->string('year');
            $table->unsignedBigInteger('auto_brand_id')->unsigned()->nullable();
            $table->foreign('auto_brand_id')->references('id')->on('auto_brands')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auto_models');
    }
}
