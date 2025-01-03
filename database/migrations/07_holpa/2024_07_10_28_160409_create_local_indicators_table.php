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
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('domain_id')->nullable()->constrained('domains')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('global_indicator_id')->nullable()->constrained('global_indicators')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('is_custom')->default(false);
            $table->text('name');
            $table->string('survey')->nullable();
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
