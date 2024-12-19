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
        Schema::table('choice_lists', function (Blueprint $table) {
            $table->renameColumn('xlsform_template_id', 'template_id');
        });

        Schema::table('choice_lists', function (Blueprint $table) {
            $table->string('template_type')->after('template_id')->default('App\\\Models\\\XlsformTemplates\\\XlsformTemplate');
        });

        Schema::table('choice_lists', function (Blueprint $table) {
            $table->string('template_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
