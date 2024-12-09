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
        Schema::table('farms', function (Blueprint $table) {
            $table->boolean('household_form_completed')->default(false)->after('team_code')->comment('Is household form completed for this farm?');
            $table->boolean('fieldwork_form_completed')->default(false)->after('household_form_completed')->comment('Is fieldwork form completed for this farm?');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            //
        });
    }
};
