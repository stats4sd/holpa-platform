<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SampleFrame\Farm;
use App\Http\Controllers\Controller;
use App\Models\SampleFrame\Location;
use App\Models\SurveyData\FarmSurveyData;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;

class SubmissionController extends Controller
{
    // This function will be called when there are new submissions to be pulled from ODK central
    public static function process(Submission $submission): void
    {
        logger('SubmissionController.process() starts...');

        // application specific business logic goes here

        // custom handling to fill in 12 months irrigation percentage in farm_survey_data record
        SubmissionController::handleIrrigationData($submission);


        // TODO: create records from nested repeat groups for seasonal_worker_seasons, livestock_uses and fish_uses


        // TODO: submissions table, fill in values to columns started_at, ended_at, survey_duration


        // TODO: farms table, update column household_form_completed, fieldwork_form_completed


        return;


        // custom handling to create new locations
        SubmissionController::handleLocationData($submission);
    }



    // custom handling for irrigation data
    // to fill in irrigation percentage for 12 months in farm_survey_data record
    public static function handleIrrigationData(Submission $submission): void
    {
        // initialise irrigation result, assume zero percentage irrigation for all months at the beginning
        $irrigationResult = [];

        for ($i = 1; $i <= 12; $i++) {
            $irrigationResult['irrigation_percentage_month_' . $i] = 0;
        }

        // existence check for irrigation data in submission content
        if (isset($submission->content['survey']['income']['water']['irrigation_season_repeat'])) {

            // get irrigation data from submission JSON content
            $irrigations = $submission->content['survey']['income']['water']['irrigation_season_repeat'];

            // handle irrigation repeat groups one by one, overwrite percentage for a month if it has higher percentage
            foreach ($irrigations as $irrigation) {
                $irrigationResult = SubmissionController::prepareIrrigationData($irrigationResult, $irrigation);
            }
        }

        // suppose there should be only one farm_survey_data for a submission id
        $farmSurveyData = FarmSurveyData::where('submission_id', $submission->id)->first();

        // update irrigation data for 12 months
        for ($i = 1; $i <= 12; $i++) {
            $farmSurveyData['irrigation_percentage_month_' . $i] = $irrigationResult['irrigation_percentage_month_' . $i];
        }

        // save farm_survey_data record
        $farmSurveyData->save();
    }


    public static function prepareIrrigationData($irrigationResult, $irrigation): array
    {
        $irrigationPercentage = $irrigation['irrigation_percentage'];

        // do nothing if irrigation_percentage is null or "null"
        if ($irrigationPercentage == null || $irrigationPercentage == "null") {
            ray('irrigation_percentage is null, do nothing');
            return $irrigationResult;
        }

        $irrigationMonths = str_getcsv($irrigation['irrigation_months'], ' ');

        foreach ($irrigationMonths as $irrigationMonth) {
            if ($irrigationPercentage > $irrigationResult['irrigation_percentage_month_' . $irrigationMonth]) {
                $irrigationResult['irrigation_percentage_month_' . $irrigationMonth] = $irrigationPercentage;
            }
        }

        return $irrigationResult;
    }


    // custom handling for location
    // to create new locations
    // TODO: this code segment is tested with location selection test form, not yet tested with Fieldwork form and Household form
    public static function handleLocationData(Submission $submission): void
    {
        // find owner (team) of this submission
        $team = $submission->xlsformVersion->xlsform->owner;

        // get locations array
        // TODO: update array location of "location" section in Fieldwork form and Household form
        $locations = $submission->content['location']['location_levels_rpt'];

        $parentLocationId = null;

        foreach ($locations as $location) {
            $locationId = $location['location_id'];

            // assumes locations are ordered from top level to bottom level
            if ($locationId == '-999') {
                // check if new location has been created in previous submission retrieval
                $parentLocation = Location::where('location_level_id', $location['level_id'])
                    ->where('parent_id', $parentLocationId)
                    ->where('code', $location['location_other'])
                    ->where('name', $location['location_other'])->first();

                // create new location if it is not existed
                if ($parentLocation == null) {
                    $parentLocation = $team->locations()->create([
                        'location_level_id' => $location['level_id'],
                        'parent_id' => $parentLocationId,
                        'code' => $location['location_other'],
                        'name' => $location['location_other'],
                    ]);
                }
            } else {
                // check if new location has been created in previous submission retrieval
                $parentLocation = Location::find($locationId);
            }

            $parentLocationId = $parentLocation->id;
        }


        // get farm details
        $rootSection = $submission->content['location'];

        $farmId = $rootSection['farm_id'];

        // if farm id is not -999, assumes farm is existed, therefore no need to do anything
        if ($farmId == '-999') {
            // check if new farm has been created in previous submission retrieval
            $farm = Farm::where('location_id', $parentLocationId)
                ->whereJsonContains('identifiers->name', $rootSection['farm_name'])->first();

            if ($farm == null) {
                // create new farm if it is not existed
                $identifiers['name'] = $rootSection['farm_name'];

                $farm = $team->farms()->create([
                    'location_id' => $parentLocationId,
                    // there is no team_code in location selection test ODK form, use a timestamp as a unique id temporary
                    // TODO: get team_code from ODK submission content
                    'team_code' => 'C' . Carbon::now()->getTimestampMs(),
                    'identifiers' => $identifiers,
                ]);
            }
        }
    }
}
