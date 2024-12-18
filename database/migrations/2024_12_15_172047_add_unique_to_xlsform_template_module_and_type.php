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
        Schema::table('xlsform_modules', function (Blueprint $table) {
            $table->unique(['form_id','form_type', 'name']);
        });
        Schema::table('xlsform_module_versions', function (Blueprint $table) {
            $table->unique(['xlsform_module_id', 'name']);
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
