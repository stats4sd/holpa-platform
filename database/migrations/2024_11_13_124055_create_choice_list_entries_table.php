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
//        Schema::create('choice_list_entries', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('choice_list_id')->constrained()->onDelete('cascade');
//
//            // entries might be global; or localised (localised ones have an owner)
//            $table->nullableMorphs('owner');
//            $table->string('name');
//            $table->json('properties')->nullable();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choice_list_entries');
    }
};
