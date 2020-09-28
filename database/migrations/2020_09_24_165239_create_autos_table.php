<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('enrrolment')->unique();
            $table->string('color');
            $table->unsignedBigInteger('visitor_id')->unsigned()->nullable();
            $table->unsignedBigInteger('auto_model_id')->unsigned()->nullable();
            $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('cascade');
            $table->foreign('auto_model_id')->references('id')->on('auto_models')->onDelete('cascade');
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
        Schema::dropIfExists('autos');
    }
}
