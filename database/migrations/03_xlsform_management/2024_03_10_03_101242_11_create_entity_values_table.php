<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entity_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('entities')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('dataset_variable_name')->constrained('dataset_variables');
            $table->text('value');
            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('entity_values');
    }
};
