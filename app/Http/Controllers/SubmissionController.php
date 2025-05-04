<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\SampleFrame\Farm;
use App\Models\SampleFrame\Location;
use App\Models\SampleFrame\LocationLevel;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Entity;
use Stats4sd\FilamentOdkLink\Models\OdkLink\EntityValue;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;

class SubmissionController extends Controller
{
    // This function will be called when there are new submissions to be pulled from ODK central
    public static function process(Submission $submission): void
    {

        static::handleLocationData($submission);

        $farm = $submission->primaryDataSubject;

        // application specific business logic goes here
        // find survey start, survey end, survey duration in minutes
        if (isset($submission->content['start']) && isset($submission->content['end'])) {

            $surveyStart = Carbon::parse($submission->content['start']);
            $surveyEnd = Carbon::parse($submission->content['end']);
            $surveyDuration = $surveyStart->diffInMinutes($surveyEnd);

            $submission->survey_started_at = $surveyStart;
            $submission->survey_ended_at = $surveyEnd;
            $submission->survey_duration = $surveyDuration;
            $submission->save();

        }
        if (Str::contains($submission->xlsformVersion->xlsform->xlsformTemplate->title, 'HOLPA Household Form')) {
            static::processHouseholdSubmission($submission);
            $farm->update(['household_form_completed' => true]);

        }

        if (Str::contains($submission->xlsformVersion->xlsform->xlsformTemplate->title, 'HOLPA Fieldwork Form')) {
            static::processFieldworkSubmission($submission);
            $farm->update(['fieldwork_form_completed' => true]);
        }


        // only run R scripts if both surveys are complete for the farm
        if ($farm->household_form_completed && $farm->fieldwork_form_completed) {

            // Run R scripts
            $RscriptPath = config('services.R.rscript_path');
            $agOut = Process::path(base_path('packages/holpa-r-scripts'))
                ->run($RscriptPath . ' data_processing/holpa_agroecology_scores.R');
            $perfOut = Process::path(base_path('packages/holpa-r-scripts'))
                ->run($RscriptPath . ' data_processing/key_performance_indicators.R');

            ray($agOut->output());
            ray($perfOut->output());
        }


    }

    public static function processHouseholdSubmission(Submission $submission): void
    {
        $farmDataEntity = $submission->rootEntity;

        // custom handling to fill in 12 months irrigation percentage in farm_survey_data record
        $farmDataEntity->addValues(SubmissionController::getIrrigationData($submission));

        // create records from nested repeat groups for seasonal_worker_seasons

        $permanentWorkersEntities = $submission->entities()->whereHas('dataset', fn($query) => $query->where('name', 'Permanent Workers'))
            ->get()
            ->each(function (Entity $entity) {
                // harmonise variable names from "perm_labourer_" to "perm_labour_"
                foreach ($entity->values as $value) {
                    if (Str::contains($value->dataset_variable_name, 'perm_labourer_')) {
                        $newKey = Str::replace('perm_labourer_', 'perm_labour_', $value->dataset_variable_name);
                        $value->dataset_variable_name = $newKey;
                        $value->save();
                    }
                }
            });

        $seasonalWorkersDataset = Dataset::firstWhere('name', 'Seasonal Workers in a Season');
        $farmDataEntity->addChildEntities(SubmissionController::handleSeasonalWorkersData($submission), $seasonalWorkersDataset);
        $farmDataEntity->addChildEntities(SubmissionController::handleSeasonalLaboursData($submission), $seasonalWorkersDataset);

        $farmProductsDataset = Dataset::firstWhere('name', 'Products');
        $farmDataEntity->addChildEntities(SubmissionController::handleProductsData($submission), $farmProductsDataset);
        $farmDataEntity->addChildEntities(SubmissionController::handleOtherProductData($submission), $farmProductsDataset);

        // handle multi-selects for new / updated data

    }

    public static function processFieldworkSubmission(Submission $submission): void
    {
        // ONLY NEEDED DURING TESTING WITH EXISTING FAKE DATA.
        // TODO: remove when we move to real beta testing
        $farmDataEntity = $submission->rootEntity;

        $siteNoManagement = [
            1 => 3,
            2 => 4,
            3 => 2,
        ];

        $farmDataEntity->children->each(function (Entity $entity) {
            if ($entity->values->pluck('dataset_variable_name')->doesntContain('management')) {

                $siteNo = $entity->values->filter(fn($value) => $value->dataset_variable_name === 'site_no')->first()?->value ?? 1;

                $entityValue = EntityValue::make([
                    'dataset_variable_name' => 'management',
                    'value' => $siteNoManagement[$siteNo] ?? 1,
                ]);

                $entity->addValues(collect([$entityValue]));
            }
        });


    }

    // ******************** //

    // custom handling for irrigation data
    // to fill in irrigation percentage for 12 months in farm_survey_data record
    /** @return Collection<EntityValue> */
    public static function getIrrigationData(Submission $submission): Collection
    {
        // existence check for irrigation data in submission content
        if (!isset($submission->content['survey']['water']['irrigation_season_repeat'])) {
            return collect();
        }

        // initialise irrigation result, assume zero percentage irrigation for all months at the beginning
        $irrigationValues = new Collection;

        for ($i = 1; $i <= 12; $i++) {
            $irrigationValues['irrigation_percentage_month_' . $i] = EntityValue::make([
                'dataset_variable_name' => 'irrigation_percentage_month_' . $i,
                'value' => 0,
            ]);
        }

        // get irrigation data from submission JSON content
        $irrigationRepeats = $submission->content['survey']['water']['irrigation_season_repeat'];

        // handle irrigation repeat groups one by one, overwrite percentage for a month if it has higher percentage
        foreach ($irrigationRepeats as $irrigationRepeat) {
            $irrigationValues = SubmissionController::prepareIrrigationData($irrigationValues, $irrigationRepeat);
        }

        return $irrigationValues->values();

    }

    /** @return Collection<EntityValue> */
    public static function prepareIrrigationData(Collection $irrigationValues, array $irrigation): Collection
    {
        $irrigationPercentage = $irrigation['irrigation_percentage'];

        // do nothing if irrigation_percentage is null or "null"
        if ($irrigationPercentage == null || $irrigationPercentage == 'null') {
            // ray('irrigation_percentage is null, do nothing');
            return $irrigationValues;
        }

        $irrigationMonths = str_getcsv($irrigation['irrigation_months'], ' ');

        foreach ($irrigationMonths as $irrigationMonth) {
            // if a month is selected multiple times, take the higher value.
            if ($irrigationPercentage > $irrigationValues['irrigation_percentage_month_' . $irrigationMonth]['value']) {

                $irrigationValues['irrigation_percentage_month_' . $irrigationMonth]['value'] = $irrigationPercentage;
            }
        }

        return $irrigationValues;
    }



    // custom handling for seasonal_worker_seasons data
    // create records from nested repeat groups for seasonal_worker_seasons
    // /survey/income/labour/household_mm_labour/seasonal_workers/seasonal_workers_s
    /** @return Collection<array> */
    public static function handleSeasonalWorkersData(Submission $submission): Collection
    {

        // existence check for nested repeat group data in submission content
        if (!isset($submission->content['survey']['labour']['household_mm_labour']['seasonal_workers'])) {
            return collect();
        }

        return collect($submission->content['survey']['labour']['household_mm_labour']['seasonal_workers'])
            ->map(function (array $seasonalWorker): ?Collection {

                if (!isset($seasonalWorker['seasonal_workers_s'])) {
                    return null;
                }

                return collect($seasonalWorker['seasonal_workers_s'])
                    ->map(function (array $seasonalWorkerItem) use ($seasonalWorker) {
                        $seasonalWorkerItem['seasonal_labour_group_num'] = $seasonalWorker['seasonal_labour_group_num'];
                        $seasonalWorkerItem['seasonal_labour_group_name'] = $seasonalWorker['seasonal_labour_group_name'];
                        $seasonalWorkerItem['household_members'] = true;

                        return $seasonalWorkerItem;
                    });

            })
            ->filter()
            ->flatten(1);

    }

    // ******************** //

    // custom handling for seasonal_worker_seasons data
    // create records from nested repeat groups for seasonal_worker_seasons
    // /survey/income/labour/labourers/sesaonal_labourers/seasonal_labourers_s/
    /** @return Collection<<array> */
    public static function handleSeasonalLaboursData(Submission $submission): Collection
    {

        // existence check for nested repeat group data in submission content
        if (!isset($submission->content['survey']['labour']['labourers']['sesaonal_labourers'])) {
            return collect();
        }

        return collect($submission->content['survey']['labour']['labourers']['sesaonal_labourers'])
            ->map(function (array $seasonalLabour): ?Collection {
                if (!isset($seasonalLabour['seasonal_labourers_s'])) {
                    return null;
                }

                return collect($seasonalLabour['seasonal_labourers_s'])
                    ->map(function (array $seasonalLabourItem) use ($seasonalLabour) {
                        $seasonalLabourItem['seasonal_labour_group_num'] = $seasonalLabour['seasonal_labour_group_num'] ?? null;
                        $seasonalLabourItem['seasonal_labour_group_name'] = $seasonalLabour['seasonal_labour_group_name'] ?? $seasonalLabour['seasonal_labourer_group'];
                        $seasonalLabourItem['household_members'] = false;

                        // harmonise var names between "seasonal labour" and "seasonal labourer" sections
                        foreach ($seasonalLabourItem as $key => $value) {
                            if (Str::contains($key, 'seasonal_labourer_')) {

                                $newKey = Str::replace('seasonal_labourer_', 'seasonal_labour_', $key);
                                $seasonalLabourItem[$newKey] = $value;
                                unset($seasonalLabourItem[$key]);
                            }
                        }

                        return $seasonalLabourItem;
                    });

            })
            ->filter()
            ->flatten(1);
    }

    // custom handling for products data
    public static function handleProductsData(Submission $submission): Collection
    {

        // get farm_products
        if (!isset($submission->content['survey']['farm_characteristics']['farm_products'])) {
            return collect();
        }

        $farmProductsList = $submission->content['survey']['farm_characteristics']['farm_products'];

        // split farm products, handle them one by one
        return collect(str_getcsv($farmProductsList, ' '))
            ->map(function (string $farmProduct) use ($submission): ?array {

                if ($farmProduct === 'other') {
                    return null;
                }

                // special handling for "crops", change it from "crops" to "crop" before handling submission content to match odk form variable names
                if ($farmProduct === 'crops') {
                    $farmProduct = 'crop';
                }

                $useData = $submission->content['survey']['farm_characteristics']["{$farmProduct}_use"];
                $salesData = $submission->content['survey']['farm_characteristics']["{$farmProduct}_sales_grp"];

                $result = [
                    'product_name' => $farmProduct,
                ];

                foreach ($useData as $key => $value) {
                    $newKey = Str::replace($farmProduct . '_', '', $key);
                    $result[$newKey] = $value;
                }

                foreach ($salesData as $key => $value) {
                    $newKey = Str::replace($farmProduct . '_', '', $key);
                    $result[$newKey] = $value;
                }

                return $result;
            })
            ->filter();
    }

    public static function handleOtherProductData(Submission $submission): Collection
    {
        if (!isset($submission->content['survey']['farm_characteristics']['other_product_use_sales'])) {
            return collect();
        }

        return collect($submission->content['survey']['farm_characteristics']['other_product_use_sales'])
            ->map(function (array $otherProduct): ?array {

                // name is included in the other_product_use_sales repeat group, so we don't need to set it.
                $result = [];

                foreach ($otherProduct as $key => $value) {
                    $newKey = Str::replace('other_prod_', '', $key);
                    $result[$newKey] = $value;
                }

                return $result;
            })
            ->filter();
    }

    public static function handleLocationData(Submission $submission): void
    {

        ray($submission->content['context']);

        /** @var Team $team */
        $team = $submission->xlsformVersion->xlsform->owner;
        $locationData = $submission->content['context']['location'];

        /** @var Collection<LocationLevel> $locationLevels */
        $locationLevels = $team->locationLevels;

        /** @var ?Location $parentLocation */
        $parentLocation = null;


        // loop through location levels; find or create locations as needed;
        foreach ($locationLevels as $level) {

            $odkName = Str::of($level->name)->slug('_')->singular();

            /** @var ?Location $location */
            $location = $level->locations()
                ->where('code', $locationData["{$odkName}_id"])
                ->first();

            if ($location) {
                $parentLocation = $location;

            } else {

                // if _id from form is -999, then it's a new entry with no pre-defined code;
                if ($locationData["{$odkName}_id"] == '-999') {

                    $code = Str::slug($locationData["{$odkName}_name"]);
                } else {
                    // in this case the location should exist, but doesn't for some reason; we can re-create it from the odk data
                    $code = $locationData["{$odkName}_id"];
                }

                $parentLocation = $level->locations()->create([
                    'code' => $code,
                    'name' => $locationData['{$odkName}_name'],
                    'parent_id' => $parentLocation?->id,
                ]);
            }
        }

        $farmId = $submission->content['context']['farm_location']['farm_id'];

        // Key identifier is 'farm_id' in form; farm_code in database, (code is unique at team-level; id is unique at database level).

        $farm = Farm::where('location_id', $parentLocation->id)
            ->where('team_code', $submission->content['context']['farm_location']['farm_id'])
            ->first();

        if ($farm) {
            $submission->primaryDataSubject()->associate($farm);
        } else {

            // farm is not found; create it!
            // if _id from form is -999, then it's a new entry with no pre-defined code;
            if ($farmId == '-999') {

                $code = Str::slug($submission->content['context']['farm_location']['farm_name']);
            } else {
                // in this case the location should exist, but doesn't for some reason; we can re-create it from the odk data
                $code = $farmId;
            }
            $farm = $parentLocation->farms()
                ->create([
                    'owner_id' => $team->id,
                    'team_code' => $farmId,
                    'identifiers' => ['name' => $submission->content['context']['farm_location']['farm_name']],
                    'latitude' => $submission->content['context']['location_confirm']['gps']['coordinates'][0],
                    'longitude' => $submission->content['context']['location_confirm']['gps']['coordinates'][1],
                    'altitude' => $submission->content['context']['location_confirm']['gps']['coordinates'][2],
                    'accuracy' => $submission->content['context']['location_confirm']['gps']['properties']['accuracy'],
                ]);

            $submission->primaryDataSubject()->associate($farm);
        }
    }
}
