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
        Schema::create('livestock_uses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farm_survey_data_id')->nullable();

            $table->json('properties')->nullable();

            // TODO: add more columns for ODK variables

            $table->foreignId('submission_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_uses');
    }
};
