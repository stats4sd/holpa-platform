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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farm_survey_data_id')->nullable();
            $table->json('properties')->nullable();

            // $table->unsignedBigInteger('product_id')->nullable();
            // $table->text('product name')->nullable();
            // $table->text('hh_consumption')->nullable();
            // $table->text('hh_cooking')->nullable();
            // $table->text('hh_building')->nullable();
            // $table->text('hh_heating')->nullable();
            // $table->text('hh_other_use')->nullable();
            // $table->text('livestock_consumption')->nullable();
            // $table->text('on_farm_use')->nullable();
            // $table->text('sales')->nullable();
            // $table->text('gifts')->nullable();
            // $table->text('waster')->nullable();
            // $table->text('other_use')->nullable();
            // $table->text('other_use_specify')->nullable();
            // $table->text('buyer')->nullable();
            // $table->text('buyer_other')->nullable();
            // $table->text('fair_price')->nullable();

            $table->text('other_prod_name')->nullable();
            $table->text('other_prod_note')->nullable();
            $table->integer('other_prod_hh_consumption')->nullable();
            $table->integer('other_prod_livestock_consumption')->nullable();
            $table->integer('other_prod_on_farm_use')->nullable();
            $table->integer('other_prod_sales')->nullable();
            $table->integer('other_prod_gifts')->nullable();
            $table->integer('other_prod_waster')->nullable();
            $table->integer('other_prod_other_use')->nullable();
            $table->text('other_prod_other_use_specify')->nullable();
            $table->text('other_prod_buyer')->nullable();
            $table->text('other_prod_buyer_other')->nullable();
            $table->integer('other_prod_fair_price')->nullable();

            $table->foreignId('submission_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
