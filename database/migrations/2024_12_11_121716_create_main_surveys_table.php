<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main_surveys', function (Blueprint $table) {
            $table->id();

            $table->text('start')->nullable();
            $table->text('end')->nullable();
            $table->text('today')->nullable();
            $table->text('deviceid')->nullable();
            $table->text('inquirer')->nullable();
            $table->text('respondent_available')->nullable();
            $table->text('non_beneficiary_screening')->nullable();
            $table->text('consent')->nullable();
            $table->text('respondent_name')->nullable();
            $table->text('respondent_phone')->nullable();

            // TODO: add more columns for ODK variables

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_surveys');
    }
};
