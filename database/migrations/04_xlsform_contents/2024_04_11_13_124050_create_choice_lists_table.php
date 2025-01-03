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
        Schema::create('choice_lists', function (Blueprint $table) {
            $table->id();
            $table->morphs('template');
            $table->string('list_name');
            $table->text('description')->nullable();
            $table->boolean('is_localisable')->default(false);
            $table->boolean('is_dataset')->default(false); // may not be used;
            $table->boolean('can_be_hidden_from_context');
            $table->boolean('has_custom_handling')->default(0);
            $table->json('properties')->nullable();
            $table->timestamps();

            $table->unique(['template_id', 'template_type', 'list_name'], 'unique_xlsform_template_id_list_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choice_lists');
    }
};
