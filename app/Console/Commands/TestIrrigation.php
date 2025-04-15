<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;

class TestIrrigation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-irrigation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get irrigations from Household form submission';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('start');

        // initialise irrigation result, assume zero percentage irrigation for all months at the beginning
        $irrigationResult = [];

        for ($i = 1; $i <= 12; $i++) {
            $irrigationResult['irrigation_percentage_month_'.$i] = 0;
        }

        // get irrigation data from submission JSON content
        $submission = Submission::first();

        $irrigations = $submission->content['survey']['income']['water']['irrigation_season_repeat'];

        // handle irrigation repeat groups one by one, overwrite percentage for a month if it has higher percentage
        foreach ($irrigations as $irrigation) {
            $irrigationResult = $this->prepareIrrigationData($irrigationResult, $irrigation);
        }

        $this->info('end');
    }

    public function prepareIrrigationData($irrigationResult, $irrigation): array
    {
        $irrigationPercentage = $irrigation['irrigation_percentage'];

        // do nothing if irrigation_percentage is null or "null"
        if ($irrigationPercentage == null || $irrigationPercentage == 'null') {
            return $irrigationResult;
        }

        $irrigationMonths = str_getcsv($irrigation['irrigation_months'], ' ');

        foreach ($irrigationMonths as $irrigationMonth) {
            if ($irrigationPercentage > $irrigationResult['irrigation_percentage_month_'.$irrigationMonth]) {
                $irrigationResult['irrigation_percentage_month_'.$irrigationMonth] = $irrigationPercentage;
            }
        }

        return $irrigationResult;
    }
}
