<?php

namespace Database\Seeders\Prep;

use App\Models\Language;
use App\Models\LookupTables\Crop;
use App\Services\HelperService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CropTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cropData = HelperService::importCsvFileToCollection(database_path('seeder_data/crops.csv'));

        foreach($cropData as $crop) {
            Crop::create([
                'name' => $crop['name'],
                'label' => [
                    'en' => $crop['label_en'],
                    'es' => $crop['label_es'],
                    'fr' => $crop['label_fr'],
                ],
            ]);
        }
    }
}
