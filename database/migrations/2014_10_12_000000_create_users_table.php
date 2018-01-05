<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 30);
            $table->string('email', 150);
            $table->string('password');
            $table->string('firstname', 100);
            $table->string('middlename', 100);
            $table->string('lastname', 100);
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHERS'])->nullable();
            $table->string('contact_number', 100)->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['CUSTOMER', 'ADMIN', 'DOCTOR', 'STAFF'])->default('CUSTOMER');
            $table->boolean('active')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
