<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnToServicesAndProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('product_status', ['active', 'inactive'])->default('active')->after('reorder_level');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->enum('service_status', ['active', 'inactive'])->default('active')->after('duration');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_status');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('service_status');
        });
    }
}
