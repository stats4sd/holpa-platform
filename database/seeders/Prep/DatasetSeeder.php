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

        $farmMainSurvey = Dataset::create(['name' => 'Farm Main Survey', 'parent_id' => NULL, 'primary_key' => 'id']);

        Dataset::create(['name' => 'Farm - Repeat Location Levels Report', 'parent_id' => $farmMainSurvey->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Farm - Products', 'parent_id' => $farmMainSurvey->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Farm - Repeat Other Product Use Sale', 'parent_id' => $farmMainSurvey->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Farm - Permanent Workers', 'parent_id' => $farmMainSurvey->id, 'primary_key' => 'id']);
        Dataset::create(['name' => 'Farm - Seasonal Workers in a Season', 'parent_id' => $farmMainSurvey->id, 'primary_key' => 'id']);
    }
}
