<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('visitor_fullname');
            $table->string('visitor_dni');
            $table->string('auto_enrrolment')->nullable();
            $table->string('auto_model')->nullable();
            $table->string('auto_color')->nullable();

            $table->unsignedBigInteger('user_id')->unsigned()->nullable();
            $table->unsignedBigInteger('visit_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('visit_id')->references('id')->on('visits');
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
        //
        Schema::dropIfExists('reports');
    }
}
