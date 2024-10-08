<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->nullable();
            $table->string('model_type')->nullable();
            $table->string('name')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('datasets')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('primary_key');
            $table->text('description')->nullable();
            $table->string('entity_model')->nullable();
            $table->boolean('external_file')->default(false)->comment('Should the csv files generated be formatted to be used with "select_one_from_external" ODK fields?');
            $table->boolean('lookup_table')->default(false)->comment('Is this dataset used as a lookup table for the survey?');
            $table->boolean('is_universal')->default(false)->comment('Is this dataset universal? If true, all owners will have access to *all* entries in the dataset. If false, some entries may be restricted to specific owners.');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};
