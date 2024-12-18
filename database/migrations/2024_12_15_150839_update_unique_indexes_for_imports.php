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
            $table->dropUnique('unique_xlsform_template_id_list_name');
        });

        Schema::table('choice_lists', function (Blueprint $table) {
            $table->unique(['template_id', 'template_type', 'list_name'], 'unique_xlsform_template_id_list_name');
        });

        Schema::table('survey_rows', function (Blueprint $table) {
            $table->dropUnique('unique_survey_rows');
        });

        Schema::table('survey_rows', function (Blueprint $table) {
            $table->unique(['template_id', 'template_type', 'name', 'type'], 'unique_survey_rows');
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
