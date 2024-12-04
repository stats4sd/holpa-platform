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
            $table->text('relevant')->nullable();
            $table->text('appearance')->nullable();
            $table->text('calculation')->nullable();
            $table->text('constraint')->nullable();
            $table->text('choice_filter')->nullable();
            $table->text('repeat_count')->nullable();
            $table->text('default')->nullable();
            $table->text('note')->nullable();
            $table->text('trigger')->nullable();
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
