<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
	        $table->string('description');
	        $table->unsignedBigInteger('type_id')->unsigned()->nullable();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
	        $table->unsignedBigInteger('presentation_id')->unsigned()->nullable();
            $table->foreign('presentation_id')->references('id')->on('presentations')->onDelete('cascade');
            $table->softDeletes(); //Nueva línea, para el borrado lógico
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
        Schema::dropIfExists('items');
    }
}
