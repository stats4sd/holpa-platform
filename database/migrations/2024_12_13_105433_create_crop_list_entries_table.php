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
        Schema::create('crop_list_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('choice_list_entry_id')->constrained('choice_list_entries');
            $table->decimal('expected_yield', 20, 2)->nullable()->comment('Must be in KG/HA. Used for calculating KPI12');
            $table->decimal('recommended_fert_use', 20, 2)->nullable()->comment('must be in KG/HA.');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_list_entries');
    }
};
