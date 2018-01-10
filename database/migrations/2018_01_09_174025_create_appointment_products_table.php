<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('appointment_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->timestamps();

            $table->foreign('appointment_id')->references('id')->on('appointments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_products');
    }
}
