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
//         user_id int
//   job_id int
//   company_id int
//   status string
//   user_cv string
        Schema::create('apply_job_list', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('user_cv',500);
            $table->timestamps();
        });

        Schema::table('apply_job_list', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('company_id');

         
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->foreign('company_id')->references('id')->on('company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apply_job_list');
    }
};
