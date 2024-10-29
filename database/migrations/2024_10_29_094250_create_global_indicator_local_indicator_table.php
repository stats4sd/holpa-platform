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
        Schema::create('global_indicator_local_indicator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('global_indicator_id')->constrained('global_indicators')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('local_indicator_id')->constrained('local_indicators')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_indicator_local_indicator');
    }
};
