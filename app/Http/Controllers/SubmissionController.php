<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SampleFrame\Farm;
use App\Http\Controllers\Controller;
use App\Models\SampleFrame\Location;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;

class SubmissionController extends Controller
{
    // This function will be called when there are new submissions to be pulled from ODK central
    public static function process(Submission $submission): void
    {
        // application specific business logic goes here

        // find survey start, survey end, survey duration in minutes
        $surveyStart = Carbon::parse($submission->content['start']);
        $surveyEnd = Carbon::parse($submission->content['end']);
        $surveyDuration = $surveyStart->diffInMinutes($surveyEnd);

        $submission->survey_started_at = $surveyStart;
        $submission->survey_ended_at = $surveyEnd;
        $submission->survey_duration = $surveyDuration;
        $submission->save();

        // skip location levels handling temporary for testing
        return;



        // find owner (team) of this submission
        $team = $submission->xlsformVersion->xlsform->owner;

        // get locations array
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
