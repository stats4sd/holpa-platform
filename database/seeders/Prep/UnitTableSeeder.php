<?php

namespace Database\Seeders\Prep;

use App\Models\LookupTables\Unit;
use App\Services\HelperService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitData = HelperService::importCsvFileToCollection(database_path('seeder_data/units.csv'));

        foreach($unitData as $unit) {
            Unit::create([
                'unit_type_id' => $unit['unit_type_id'],
                'name' => $unit['name'],
                'label' => [
                    'en' => $unit['label_en'],
                    'es' => $unit['label_es'],
                    'fr' => $unit['label_fr'],
                ],
                'conversion_rate' => $unit['conversion_rate'],
            ]);
        }
    }
}
