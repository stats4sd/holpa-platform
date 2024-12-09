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
        Schema::table('choice_lists', function (Blueprint $table) {
            $table->unique(['xlsform_template_id', 'list_name'], 'unique_xlsform_template_id_list_name');
        });

        Schema::table('choice_list_entries', function (Blueprint $table) {
            $table->unique(['name', 'choice_list_id'], 'unique_name_choice_list_id');
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
