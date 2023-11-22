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
        Schema::create('role_tb', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
        });

        DB::table('role_tb')->insert([
            [ 'role_name' => 'user'],
            [ 'role_name' => 'employer'],
            [ 'role_name' => 'manager'],
            [ 'role_name' => 'admin'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_tb');
    }
};
