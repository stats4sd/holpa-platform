∑<?php

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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('languages_progress')->after('diet_diversity_module_version_id')->default('not_started');
            $table->string('sampling_progress')->after('languages_progress')->default('not_started');
            $table->string('pba_progress')->after('sampling_progress')->default('not_started');
            $table->string('lisp_progress')->after('pba_progress')->default('not_started');
            $table->string('pilot_progress')->after('lisp_progress')->default('not_started');
            $table->string('data_collection_progress')->after('pilot_progress')->default('not_started');
            $table->string('data_analysis_progress')->after('data_collection_progress')->default('not_started');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            //
        });
    }
};
