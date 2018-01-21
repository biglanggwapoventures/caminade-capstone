<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pet_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('appointment_id');
            $table->unsignedInteger('pet_id');
            $table->text('remarks')->nullable();
            $table->date('log_date')->nullable();
            $table->time('log_time')->nullable();
            $table->timestamps();

            $table->foreign('appointment_id')->references('id')->on('appointments')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('pet_logs');
    }
}
