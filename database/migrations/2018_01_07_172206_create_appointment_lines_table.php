<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('appointment_id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('pet_id');
            $table->timestamps();

            $table->foreign('appointment_id')->references('id')->on('appointments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('appointment_lines');
    }
}
