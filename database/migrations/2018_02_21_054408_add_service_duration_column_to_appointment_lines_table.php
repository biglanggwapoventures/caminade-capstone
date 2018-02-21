<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceDurationColumnToAppointmentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointment_lines', function (Blueprint $table) {
            $table->unsignedInteger('service_duration')->after('service_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointment_lines', function (Blueprint $table) {
            $table->dropColumn('service_duration');
        });
    }
}
