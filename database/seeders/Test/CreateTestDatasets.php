<?php

namespace Database\Seeders\Test;

use App\Filament\App\Clusters\LookupTables\Resources\CropProductResource;
use App\Models\LookupTables\Animal;
use App\Models\LookupTables\AnimalProduct;
use App\Models\LookupTables\Crop;
use App\Models\LookupTables\CropProduct;
use App\Models\LookupTables\Enumerator;
use App\Models\LookupTables\Unit;
use Illuminate\Database\Seeder;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Dataset;

// Temp class - eventually the lookup table datasets should be created based on the xlsform template uploads
class CreateTestDatasets extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dataset::updateOrCreate(
            ['name' => 'Animals'],
            ['entity_model' => Animal::class, 'lookup_table' => true, 'primary_key' => 'id']
        );
        Dataset::updateOrCreate(
            ['name' => 'Animal Products'],
            ['entity_model' => AnimalProduct::class, 'lookup_table' => true, 'primary_key' => 'id']
        );
        Dataset::updateOrCreate(
            ['name' => 'Crops'],
            ['entity_model' => Crop::class, 'lookup_table' => true, 'primary_key' => 'id']
        );
        Dataset::updateOrCreate(
            ['name' => 'Crop Products'],
            ['entity_model' => CropProduct::class, 'lookup_table' => true, 'primary_key' => 'id']
        );
        Dataset::updateOrCreate(
            ['name' => 'Enumerators'],
            ['entity_model' => Animal::class, 'lookup_table' => true, 'primary_key' => 'id']
        );
        Dataset::updateOrCreate(
            ['name' => 'Units'],
            ['entity_model' => Unit::class, 'lookup_table' => true, 'primary_key' => 'id']
        );
         Dataset::updateOrCreate(
            ['name' => 'Enumerators'],
            ['entity_model' => Enumerator::class, 'lookup_table' => true, 'primary_key' => 'id']
        );
    }
}
