<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('teams')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('location_level_id')->constrained('location_levels')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('parent_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();

            $table->timestamps();


            $table->unique(['owner_id', 'code']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
