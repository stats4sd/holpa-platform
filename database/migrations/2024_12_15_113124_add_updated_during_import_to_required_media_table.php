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
        Schema::table('required_media', function (Blueprint $table) {
            $table->boolean('updated_during_import')->default(0)->after('exists_on_odk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('required_media', function (Blueprint $table) {
            //
        });
    }
};
