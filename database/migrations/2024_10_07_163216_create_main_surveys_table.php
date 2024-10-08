<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main_surveys', function (Blueprint $table) {
            $table->id();

            $table->text('start')->nullable();
            $table->text('end')->nullable();
            $table->text('today')->nullable();
            $table->text('deviceid')->nullable();
            $table->text('inquirer')->nullable();
            $table->foreignId('submission_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_surveys');
    }
};
