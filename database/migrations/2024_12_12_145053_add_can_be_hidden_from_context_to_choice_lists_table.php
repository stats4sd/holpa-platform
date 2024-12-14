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
            $table->boolean('can_be_hidden_from_context')->default(0)->after('is_dataset');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('choice_lists', function (Blueprint $table) {
            //
        });
    }
};
