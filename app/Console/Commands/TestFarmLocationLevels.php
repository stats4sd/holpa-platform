<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use App\Services\HelperService;
use Illuminate\Console\Command;
use App\Models\SampleFrame\Farm;

class TestFarmLocationLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-farm-location-levels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find all location levels of a farm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start');

        // find farm model
        $farm = Farm::find(338);

        $this->comment('farm_id: ' . $farm->id);
        $this->comment('farm_name: ' . $farm->identifiers['name']);

        // array for storing farm and location levels details
        $array = [];

        $array["farm_name"] = $farm->identifiers['name'];
        $array["farm_id"] = $farm->id;

        $tempLocation = $farm->location;

        // find all location levels until there is no more parent location
        do {
            $this->comment('=====');

            // a location Id field name should be lowercase, with underscore instead of hypen, e.g. sub_district
            $locationIdFieldName = Str::lower(Str::replace('-', '_', $tempLocation->locationLevel->name));
            $this->comment($locationIdFieldName . '_id: ' . $tempLocation->id);
            $this->comment($locationIdFieldName . '_name: ' . $tempLocation->name);

            $array[$locationIdFieldName . '_name'] = $tempLocation->name;
            $array[$locationIdFieldName . '_id'] = $tempLocation->id;

            $tempLocation = $tempLocation->parent;
        } while ($tempLocation != null);

        $this->comment('*****');
        $reversedArray = array_reverse($array);

        dump($array);
        dump($reversedArray);

        dump(json_encode($array));
        dump(json_encode($reversedArray));

        $this->comment('*****');

        $this->info('end');

        // ========== //

        dump(HelperService::findFarmLocationDetails(338));
        dump(HelperService::findFarmLocationDetails(339));
    }
}
