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
        Schema::table('xlsform_template_module_types', function (Blueprint $table) {
            $table->unique(['name']);
        });
        Schema::table('xlsform_template_modules', function (Blueprint $table) {
            $table->unique(['xlsform_template_id', 'xlsform_template_module_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('xlsform_template_module_and_type', function (Blueprint $table) {
            //
        });
    }
};
