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
            // after running this migration file, column "owner_type" is still existed.
            // suspects both column "owner_type" and "owner_id" need to exist when polymorphic relationship is created by below statement:
            // $table->nullableMorphs('owner');
            Schema::dropIfExists('owner_type');
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
