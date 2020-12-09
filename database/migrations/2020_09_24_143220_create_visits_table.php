<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('visitor_id')->unsigned();
            $table->unsignedBigInteger('worker_id')->unsigned()->nullable();
            $table->unsignedBigInteger('auto_id')->unsigned()->nullable();
            $table->unsignedBigInteger('department_id')->unsigned()->nullable();
            $table->string('status');
         

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('cascade');
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('set null');
            $table->foreign('auto_id')->references('id')->on('autos')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->timestamp('date_attendance');
            $table->time('entry_time');
            $table->time('departure_time');
            $table->timestamps();
            $table->softDeletes(); //Nueva línea, para el borrado lógico

            // Add the constraint
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
        Schema::drop('visits');
    }
}
