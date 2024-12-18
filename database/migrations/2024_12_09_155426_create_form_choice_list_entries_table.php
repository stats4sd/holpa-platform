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
        Schema::create('form_choice_list_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('choice_list_id')->constrained()->onDelete('cascade');
            $table->nullableMorphs('owner');
            $table->string('name');
            $table->json('properties');
            $table->boolean('updated_during_import')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_choice_list_entries');
    }
};
