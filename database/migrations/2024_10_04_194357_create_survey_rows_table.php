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
        Schema::create('survey_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('xlsform_template_id');
            $table->string('name');

            $table->string('type');
            $table->boolean('required')->default(false);
            $table->string('relevant')->nullable();
            $table->string('appearance')->nullable();
            $table->string('calculation')->nullable();
            $table->string('constraint')->nullable();
            $table->string('choice_filter')->nullable();
            $table->string('repeat_count')->nullable();
            $table->string('default')->nullable();
            $table->string('note')->nullable();
            $table->string('trigger')->nullable();
            $table->json('properties')->nullable(); // catchall for other props;


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_rows');
    }
};
