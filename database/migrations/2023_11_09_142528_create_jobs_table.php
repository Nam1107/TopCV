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
            $table->string('desc',500);
            $table->string('exp_required');
            $table->integer('quantity');
            $table->string('level_required');
            $table->string('field_of_job',500);
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('created_by');
            $table->datetime('expire_at');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
