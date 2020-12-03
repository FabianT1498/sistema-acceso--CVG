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
            $table->string('enrrolment', 7);
            $table->string('color');
            $table->unsignedBigInteger('auto_model_id')->unsigned()->nullable();
            $table->unsignedBigInteger('user_id')->unsigned();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('auto_model_id')->references('id')->on('auto_models')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
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
