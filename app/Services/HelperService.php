<?php

namespace App\Services;
use App\Models\SampleFrame\Farm;
use App\Models\Team;
use Filament\Facades\Filament;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HelperService
{
    public static function getModels(): Collection
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                return sprintf(
                    '\%s%s',
                    Container::getInstance()->getNamespace(),
                    str_replace('/', '\\', substr($path, 0, strrpos($path, '.')))
                );
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        !$reflection->isAbstract();
                }

                return $valid;
            });

        return $models->values();
    }

    public static function getOdkVariablesToIgnore(): array
    {
        return [
            '__id',
            'instanceID',
            'meta',
            'deviceid',
            'start_time',
            'end_time',
            '_id',
            'uuid',
            '__version__',
            '_xform_id_string',
            '_uuid',
            '_attachments',
            '_status',
            '_geolocation',
            '_submission_time',
            '_tags',
            '_notes',
            '_validation_status',
            '_submitted_by',
        ];
    }

    public static function importCsvFileToCollection(string $filePath): Collection
    {
        // Read CSV file content, call trim() to remove last blank line
        $csvFileContent = trim(File::get($filePath));

        // remove newlines within cells
        $csvFileContent = preg_replace('/\"(.+)\n\"/', '$1', $csvFileContent);

        // remove \u{FEFF} within cells
        $csvFileContent = str_replace("\u{FEFF}", "", $csvFileContent);

        // Split by new line. Use the PHP_EOL constant for cross-platform compatibility.
        $lines = explode(PHP_EOL, $csvFileContent);

        // Extract the header and convert it into a Laravel collection.
        $header = collect(str_getcsv(array_shift($lines)));

        // Map through the rows and combine them with the header to produce the final collection.
        return collect($lines)->map(function ($row) use ($header) {
            return $header->combine(str_getcsv($row));
        });
    }

    // helper function to return the currently selected team in a Filament panel.
    // useful because it always returns a Team::class (or null), so you can use it in a type hint.
    public static function getSelectedTeam(): Team|Model|null
    {
        if (Filament::hasTenancy() && Filament::getTenant() instanceof Team) {
            return Filament::getTenant();
        }

        return null;
    }

    // helper function to get model by table name
    public static function getModelByTablename($tableName)
    {
        // get all models
        $classes = HelperService::getModels();

        foreach ($classes as $class) {
            $model = new $class;

            if ($model->getTable() == $tableName) {
                // found a matched model
                return $model;
            }
        }

        // cannot find a matched model
        return null;
    }

    // find farm's full location details for data export to excel file
    public static function findFarmLocationDetails(string $farmId): array
    {
        // find farm model
        $farm = Farm::find($farmId);

        // array for storing farm and location levels details
        $array = [];

        $array["farm_name"] = $farm->identifiers['name'];
        $array["farm_id"] = $farm->id;

        $tempLocation = $farm->location;

        // find all location levels until there is no more parent location
        do {
            // a location Id field name should be lowercase, with underscore instead of hypen, e.g. sub_district
            $locationIdFieldName = Str::lower(Str::replace('-', '_', $tempLocation->locationLevel->name));

            $array[$locationIdFieldName . '_name'] = $tempLocation->name;
            $array[$locationIdFieldName . '_id'] = $tempLocation->id;

            $tempLocation = $tempLocation->parent;
        } while ($tempLocation != null);

        $reversedArray = array_reverse($array);

        return $reversedArray;
    }


}
