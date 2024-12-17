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
        Schema::table('crop_list_entries', function (Blueprint $table) {
            $table->foreignId('farm_survey_data_id')->nullable()->after('id');
            $table->json('properties')->nullable()->after('farm_survey_data_id');

            $table->foreignId('submission_id')->nullable()->after('recommended_fert_use');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crop_list_entries', function (Blueprint $table) {
            //
        });
    }
};
