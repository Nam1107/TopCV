<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // name string
    // sex string
    // phone string
    // address string
    // city string
    // role int
    // avatar string
    // email string
    // password string
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('sex');
            $table->string('phone');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('address');
            $table->string('district');
            $table->string('role');
            $table->string('avatar');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
