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
        Schema::create('form_survey_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('xlsform_id');

            // Does this survey row replace a surveyRow from the template?
            $table->foreignId('replaces_id')->nullable();

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

            $table->unique(['xlsform_id', 'name', 'type'], 'unique_form_survey_rows');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_survey_rows');
    }
};
