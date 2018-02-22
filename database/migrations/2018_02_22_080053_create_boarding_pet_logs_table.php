<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardingPetLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boarding_pet_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('boarding_id');
            $table->unsignedInteger('pet_id');
            $table->date('log_date');
            $table->time('log_time');
            $table->text('remarks');
            $table->timestamps();

            $table->foreign('boarding_id')->references('id')->on('boardings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pet_id')->references('id')->on('pets')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boarding_pet_logs');
    }
}
