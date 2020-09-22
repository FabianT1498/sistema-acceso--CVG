<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_delivery_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity_released');
            $table->unsignedBigInteger('stock_id')->unsigned()->nullable();
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
            $table->unsignedBigInteger('delivery_detail_id')->unsigned()->nullable();
            $table->foreign('delivery_detail_id')->references('id')->on('delivery_details')->onDelete('cascade');
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
        Schema::dropIfExists('stock_delivery_details');
    }
}
