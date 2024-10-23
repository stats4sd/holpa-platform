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
        Schema::create('choice_list_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('choice_list_id')->constrained()->onDelete('cascade');

            // entries may be owned by a team; they may not be. They are never owned by a team if the choice list is not localisable.
            $table->nullableMorphs('owner');
            $table->string('name');

            // labels are stored as language_strings;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choice_list_entries');
    }
};
