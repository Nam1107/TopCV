<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('address');
            $table->string('district');
            $table->string('city');
            $table->string('phone');
            $table->string('logo',500);
            $table->string('detail');
            $table->string('url_page',500);
            $table->integer('follow_count');
            $table->timestamps();
        });

        Schema::table('company', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id');
         
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
