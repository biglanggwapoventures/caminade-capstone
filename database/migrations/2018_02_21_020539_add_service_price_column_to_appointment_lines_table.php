<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServicePriceColumnToAppointmentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointment_lines', function (Blueprint $table) {
            $table->decimal('service_price', 13, 2)->after('pet_id')->nullable();
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
            $table->dropColumn('service_price');
        });
    }
}
