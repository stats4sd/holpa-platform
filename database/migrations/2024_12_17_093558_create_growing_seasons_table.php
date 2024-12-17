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
        Schema::create('growing_seasons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farm_survey_data_id')->nullable();
            $table->json('properties')->nullable();

            // avoid to use month name like Jan, Feb, Mar as they are in English.
            // use month number as it should be commonly used in different languages.

            $table->decimal('month_1', 5, 2)->nullable();
            $table->decimal('month_2', 5, 2)->nullable();
            $table->decimal('month_3', 5, 2)->nullable();
            $table->decimal('month_4', 5, 2)->nullable();
            $table->decimal('month_5', 5, 2)->nullable();
            $table->decimal('month_6', 5, 2)->nullable();
            $table->decimal('month_7', 5, 2)->nullable();
            $table->decimal('month_8', 5, 2)->nullable();
            $table->decimal('month_9', 5, 2)->nullable();
            $table->decimal('month_10', 5, 2)->nullable();
            $table->decimal('month_11', 5, 2)->nullable();
            $table->decimal('month_12', 5, 2)->nullable();

            $table->foreignId('submission_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growing_seasons');
    }
};
