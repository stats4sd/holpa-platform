<?php

namespace Database\Seeders\Prep;

use App\Models\Dataset;
use App\Models\SurveyData\FarmSurveyData;
use App\Models\SurveyData\Fish;
use App\Models\SurveyData\FishUse;
use Illuminate\Database\Seeder;

class DatasetSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('datasets')->delete();

        // datasets mentioned in Data Structure excel file
        $farmSurveyDataset = Dataset::create(['name' => 'Farm Survey Data', 'parent_id' => NULL, 'primary_key' => 'id', 'entity_model' => FarmSurveyData::class,]);
        Dataset::create(['name' => 'Crops', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Ecological Practices', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Farms', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Fieldwork Sites', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        $fish = Dataset::create(['name' => 'Fish', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id', 'entity_model' => Fish::class,]);
        Dataset::create(['name' => 'Fish Uses', 'parent_id' => $fish->id, 'primary_key' => 'id', 'entity_model' => FishUse::class,]);
        $livestock = Dataset::create(['name' => 'Livestock', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Livestock Uses', 'parent_id' => $livestock->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Locations', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Permanent Workers', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Products', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Seasonal Workser in a Season', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);

        // datasets not mentioned in Data Structure excel file
        Dataset::create(['name' => 'Growing Seasons (Irrigation)', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Sites', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
    }
}
