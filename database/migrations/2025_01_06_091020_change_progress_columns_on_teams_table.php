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
        DB::table('teams')->update([
            'lisp_progress' => DB::raw("IF(lisp_progress = 'complete', 1, 0)"),
            'sampling_progress' => DB::raw("IF(sampling_progress = 'complete', 1, 0)"),
            'languages_progress' => DB::raw("IF(languages_progress = 'complete', 1, 0)"),
            'pba_progress' => DB::raw("IF(pba_progress = 'complete', 1, 0)"),
        ]);
        
        Schema::table('teams', function (Blueprint $table) {
            $table->renameColumn('lisp_progress', 'lisp_complete');
            $table->renameColumn('sampling_progress', 'sampling_complete');
            $table->renameColumn('languages_progress', 'languages_complete');
            $table->renameColumn('pba_progress', 'pba_complete');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->boolean('lisp_complete')->default(false)->change();
            $table->boolean('sampling_complete')->default(false)->change();
            $table->boolean('languages_complete')->default(false)->change();
            $table->boolean('pba_complete')->default(false)->change();
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
