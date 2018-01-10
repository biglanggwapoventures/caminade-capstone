<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('pet_category_id')->nullable();
            $table->unsignedInteger('pet_breed_id');
            $table->unsignedInteger('pet_reproductive_alteration_id');
            $table->string('name', 150);
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE'])->default('MALE');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pet_category_id')->references('id')->on('pet_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pet_breed_id')->references('id')->on('pet_breeds')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pet_reproductive_alteration_id')->references('id')->on('pet_reproductive_alterations')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pets');
    }
}
