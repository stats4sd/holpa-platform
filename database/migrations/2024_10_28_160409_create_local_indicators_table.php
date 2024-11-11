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
        Schema::create('local_indicators', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('theme_id')->nullable()->constrained('themes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('global_indicator_id')->nullable()->constrained('global_indicators')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_indicators');
    }
};
