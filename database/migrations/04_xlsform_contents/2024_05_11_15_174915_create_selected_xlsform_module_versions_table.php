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
        Schema::create('selected_xlsform_module_versions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('xlsform_module_version_id');
            $table->foreign('xlsform_module_version_id', 'sxmv_xlsform_module_version_id_foreign')->references('id')->on('xlsform_module_versions');
            $table->foreignId('xlsform_id')->constrained('xlsforms')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selected_xlsform_module_versions');
    }
};
