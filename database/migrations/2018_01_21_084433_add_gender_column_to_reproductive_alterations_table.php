<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGenderColumnToReproductiveAlterationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pet_reproductive_alterations', function (Blueprint $table) {
            $table->enum('gender', ['MALE', 'FEMALE'])->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pet_reproductive_alterations', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
}
