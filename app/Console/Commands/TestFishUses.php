<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use App\Models\SurveyData\FishUse;
use App\Models\SurveyData\FarmSurveyData;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;

class TestFishUses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get fish uses data from Household form submission';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        $xlsform = Xlsform::find(1);

        dd($xlsform->surveyRows->sortBy('row_number')->pluck('name', 'row_number'));
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
