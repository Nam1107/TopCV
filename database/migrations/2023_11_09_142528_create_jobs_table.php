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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('salary');
            $table->string('sex_required');
            $table->string('desc');
            $table->string('exp_required');
            $table->int('quantity');
            $table->string('level_required');
            $table->string('field_of_job');
            $table->int('company_id')->unsigned();
            $table->int('created_by')->unsigned();
            $table->string('status');
            $table->datetime('expire_at');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('company');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
