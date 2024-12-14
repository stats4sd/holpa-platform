<?php

namespace Database\Seeders\Prep;

use App\Models\Reference\AgPractice;
use App\Models\Reference\ClimateMitigationScore;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClimateMitigationScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Monocultures annual')->id,
            'score' => 2.666666667,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Monocultures perennial')->id,
            'score' => 3.833333333,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Agroforestry')->id,
            'score' => 4.66,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Vegetation clearance')->id,
            'score' => 1.666666667,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Green manure')->id,
            'score' => 3.0,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Crop rotation')->id,
            'score' => 3.0,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Embedded natural (hedgerows) - fallow')->id,
            'score' => 4.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Embedded natural (hedgerows) - hedgerows')->id,
            'score' => 4.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Homegarden')->id,
            'score' => 4.66,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Intercropping')->id,
            'score' => 4.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Vegetation clearance')->id,
            'score' => 1.666666667,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Mulching')->id,
            'score' => 3.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Embedded natural (hedgerows) - natural strips/vegetation')->id,
            'score' => 4.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Embedded natural (hedgerows) - pollinator strips')->id,
            'score' => 4.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Push-Pull')->id,
            'score' => 4.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Other')->id,
            'score' => 3.630888889,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Biochar')->id,
            'score' => 2.66,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Drip irrigation')->id,
            'score' => 2.66,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Microdosing')->id,
            'score' => 2.66,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Reduced tillage')->id,
            'score' => 3.66,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Rice_Biochar')->id,
            'score' => 3.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Rice_Green manure')->id,
            'score' => 4.0,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Rice_Microdosing')->id,
            'score' => 3.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Rice-fish integration')->id,
            'score' => 3.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Alternate wetting and drying')->id,
            'score' => 3.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Improved feed quality')->id,
            'score' => 4.0,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Planting N-fixing legumes')->id,
            'score' => 3.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Rotational grazing')->id,
            'score' => 3.33,
        ]);
        ClimateMitigationScore::create([
            'region_id' => '202',
            'ag_practice_id' => AgPractice::firstWhere('name', 'Improved feed quality')->id,
            'score' => 3.0,
        ]);

    }
}
