<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('xlsform_module_version_locale', function (Blueprint $table) {
            $table->id();
            $table->foreignId('xlsform_module_version_id')->constrained('xlsform_module_versions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('locale_id')->default(0)->constrained('locales')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('has_language_strings')->default(0);
            $table->boolean('needs_update')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xlsform_template_languages');
    }
};
