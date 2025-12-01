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
        Schema::create('temp_results', function (Blueprint $table) {
            $table->id();
            $table->string('country_id')->constrained()->nullable();
            $table->string('gender')->nullable();
            $table->string('education_level')->nullable();
            $table->integer('age')->nullable();
            $table->decimal('farm_size', 10, 2)->nullable();
            // gps
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_results');
    }
};
