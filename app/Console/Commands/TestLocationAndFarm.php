<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Team;
use Illuminate\Support\Str;
use App\Services\HelperService;
use Illuminate\Console\Command;
use App\Models\SampleFrame\Farm;
use App\Models\SampleFrame\Location;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;

class TestLocationAndFarm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * * This is a quick command program to ease testing
     *
     * @var string
     */
    protected $signature = 'app:test-location-and-farm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle location and farm in a submission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start');


        // get a submission with new locations and new farm
        $submission = Submission::find(1);

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
                    'team_code' => 'C' . Carbon::now()->getTimestampMs(),
                    'identifiers' => $identifiers,
                ]);
            }
        }


        $this->info('end');
    }
}
