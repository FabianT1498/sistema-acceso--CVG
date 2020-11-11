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
            $table->unsignedBigInteger('user_id')->unsigned()->nullable();
            $table->unsignedBigInteger('visitor_id')->unsigned();
            $table->unsignedBigInteger('worker_id')->unsigned()->nullable();
            $table->unsignedBigInteger('auto_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('cascade');
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('set null');
            $table->foreign('auto_id')->references('id')->on('autos')->onDelete('set null');
            $table->timestamp('date_attendance');
            $table->timestamps();
            $table->softDeletes(); //Nueva línea, para el borrado lógico
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
    }
}
