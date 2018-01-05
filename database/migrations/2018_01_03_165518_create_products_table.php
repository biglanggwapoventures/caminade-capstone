<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_category_id');
            $table->unsignedInteger('supplier_id');
            $table->string('name', 100);
            $table->string('code', 30);
            $table->string('description', 150);
            $table->decimal('price', 13, 2);
            $table->decimal('stock', 13, 2);
            $table->decimal('reorder_level', 13, 2);
            $table->text('photo_path');
            $table->timestamps();

            $table->foreign('product_category_id')->references('id')->on('product_categories')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
