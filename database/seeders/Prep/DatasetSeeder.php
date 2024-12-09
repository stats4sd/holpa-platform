<?php

namespace Database\Seeders\Prep;

use App\Models\Dataset;
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

        $farmSurveyDataset = Dataset::create(['name' => 'Farm Survey Data', 'parent_id' => NULL, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Products', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Permanent Workers', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Seasonal Workser in a Season', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Ecological Practices', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Crops', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        $livestock = Dataset::create(['name' => 'Livestock', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Livestock Uses', 'parent_id' => $livestock->id, 'primary_key' => 'id']);
        $fish = Dataset::create(['name' => 'Fish', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Fish Uses', 'parent_id' => $fish->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Growing Seasons (Irrigation)', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Sites', 'parent_id' => $farmSurveyDataset->id, 'primary_key' => 'id']);
    }
}
