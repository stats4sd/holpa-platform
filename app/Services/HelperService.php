<?php

namespace App\Services;

use App\Models\SampleFrame\Farm;
use Illuminate\Support\Str;

class HelperService
{
    // find farm's full location details for data export to Excel file
    public static function findFarmLocationDetails(string $farmId): array
    {
        // find farm model
        $farm = Farm::find($farmId);

        // array for storing farm and location levels details
        $array = [];

        $array['farm_name'] = $farm->identifiers['name'];
        $array['farm_id'] = $farm->id;

        $tempLocation = $farm->location;

        // find all location levels until there is no more parent location
        do {
            // a location ID field name should be lowercase, with underscore instead of hypen, e.g. sub_district
            $locationIdFieldName = Str::lower(Str::replace('-', '_', $tempLocation->locationLevel->name));

            $array[$locationIdFieldName . '_name'] = $tempLocation->name;
            $array[$locationIdFieldName . '_id'] = $tempLocation->id;

            $tempLocation = $tempLocation->parent;
        } while ($tempLocation != null);

        return array_reverse($array);
    }
}
