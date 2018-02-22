<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardingProductsUsedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boarding_products_useds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('boarding_id');
            $table->unsignedInteger('product_id');
            $table->decimal('unit_price', 13, 2)->default('0');
            $table->decimal('discount', 13, 2)->nullable();
            $table->unsignedInteger('quantity');
            $table->date('date_used');
            $table->time('time_used');
            $table->timestamps();

            $table->foreign('boarding_id')->references('id')->on('boardings')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('boarding_products_useds');
    }
}
