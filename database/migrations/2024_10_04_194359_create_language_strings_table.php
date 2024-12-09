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
        Schema::create('language_strings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('xlsform_template_language_id');
            $table->morphs('linked_entry');
            $table->foreignId('language_string_type_id');
            $table->text('text');

            $table->boolean('updated_during_import')->default(false); // used to track if the row was updated during import of a new version of the XlsformTemplate file;
            $table->timestamps();

            $table->unique(['xlsform_template_language_id', 'linked_entry_id', 'linked_entry_type', 'language_string_type_id'], 'unique_language_string');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_strings');
    }
};
