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
        Schema::table('farm_survey_data', function (Blueprint $table) {
            $table->decimal('irrigation_percentage_month_1', 5, 2)->nullable()->after('gps_location_alt');
            $table->decimal('irrigation_percentage_month_2', 5, 2)->nullable()->after('irrigation_percentage_month_1');
            $table->decimal('irrigation_percentage_month_3', 5, 2)->nullable()->after('irrigation_percentage_month_2');
            $table->decimal('irrigation_percentage_month_4', 5, 2)->nullable()->after('irrigation_percentage_month_3');
            $table->decimal('irrigation_percentage_month_5', 5, 2)->nullable()->after('irrigation_percentage_month_4');
            $table->decimal('irrigation_percentage_month_6', 5, 2)->nullable()->after('irrigation_percentage_month_5');
            $table->decimal('irrigation_percentage_month_7', 5, 2)->nullable()->after('irrigation_percentage_month_6');
            $table->decimal('irrigation_percentage_month_8', 5, 2)->nullable()->after('irrigation_percentage_month_7');
            $table->decimal('irrigation_percentage_month_9', 5, 2)->nullable()->after('irrigation_percentage_month_8');
            $table->decimal('irrigation_percentage_month_10', 5, 2)->nullable()->after('irrigation_percentage_month_9');
            $table->decimal('irrigation_percentage_month_11', 5, 2)->nullable()->after('irrigation_percentage_month_10');
            $table->decimal('irrigation_percentage_month_12', 5, 2)->nullable()->after('irrigation_percentage_month_11');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farm_survey_data', function (Blueprint $table) {
            //
        });
    }
};
