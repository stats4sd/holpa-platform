<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use App\Models\SurveyData\FishUse;
use App\Models\SurveyData\FarmSurveyData;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;

class TestFishUses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-fish-uses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get fish uses data from Household form submission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start');

        // get irrigation data from submission JSON content
        $submission = Submission::first();

        ray('submission ' . $submission->id);

        // suppose there should be only one farm_survey_data for a submission id
        $farmSurveyData = FarmSurveyData::where('submission_id', $submission->id)->first();


        // existence check for nested repeat group data in submission content
        if (isset($submission->content['survey']['income']['fish_production']['fish_repeat'])) {
            ray('fish_repeat data found');

            $class = \App\Models\SurveyData\FishUse::class;
            $model = new $class;
            $columnNames = Schema::getColumnListing($model->getTable());
            ray($columnNames);

            $fishRepeats = $submission->content['survey']['income']['fish_production']['fish_repeat'];

            foreach ($fishRepeats as $fishRepeat) {

                if (isset($fishRepeat['fish_production_repeat'])) {
                    ray('fish_production_repeat data found');

                    // get data from submission JSON content
                    $fishProductionRepeats = $fishRepeat['fish_production_repeat'];

                    // handle nested repeat groups one by one
                    foreach ($fishProductionRepeats as $fishProductionRepeat) {
                        ray($fishProductionRepeat);

                        $result = $this->prepareFishUseData($fishProductionRepeat, $columnNames);

                        $result['submission_id'] = $submission->id;
                        $result['farm_survey_data_id'] = $farmSurveyData->id;

                        ray($result);

                        $fishUse = FishUse::create($result);
                    }
                } else {
                    ray('fish_production_repeat data not found');
                }
            }
        } else {
            ray('fish_repeat data not found');
        }

        $this->info('end');
    }


    public function prepareFishUseData($fishProductionRepeat, $columnNames): array
    {
        $result = [];

        foreach ($fishProductionRepeat as $key => $value) {
            if (in_array($key, $columnNames)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
