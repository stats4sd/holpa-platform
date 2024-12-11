<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('performance_fish_uses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('main_survey_id')->nullable();

            // TODO: add more columns for ODK variables

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_fish_uses');
    }
};
