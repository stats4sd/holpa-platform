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

        $householdMainSurvey = Dataset::create(['name' => 'Household - Main Survey', 'parent_id' => NULL, 'primary_key' => 'id']);
        $fieldworkMainSurvey = Dataset::create(['name' => 'Fieldwork - Main Survey', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Household - Repeat Location Levels Report', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Products', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Household - Repeat Other Product Use Sale', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Permanent Workers', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Household - Repeat Permanent Labourers', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Seasonal Workers', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Household - Repeat Seasonal Labourers', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Crop Lands', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Crop Productive Details', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Primary Livestock Details', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Primary Livestock Uses', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Fish', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Fish Uses', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Growing Seasons', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Household - Repeat Sites', 'parent_id' => $householdMainSurvey->id, 'primary_key' => 'id']);
    }
}
