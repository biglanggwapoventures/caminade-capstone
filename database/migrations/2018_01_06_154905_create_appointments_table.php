<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('doctor_id')->nullable();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->text('remarks')->nullable();
            $table->enum('appointment_status', ['PENDING', 'APPROVED', 'DENIED', 'CANCELLED']);
            $table->text('status_remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
