<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Carbon\Carbon;
use App\Models\SampleFrame\Farm;
use Illuminate\Support\Collection;
use App\Models\SampleFrame\Location;
use Illuminate\Support\Str;
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

        if (Str::contains($submission->xlsformVersion->xlsform->xlsformTemplate->name, 'HOLPA Household Form')) {
            static::processHouseholdSubmission($submission);
        }

        if (Str::contains($submission->xlsformVersion->xlsform->xlsformTemplate->name, 'HOLPA Fieldwork Form')) {
            static::processFieldworkSubmission($submission);
        }


        // TODO: farms table, update column household_form_completed, fieldwork_form_completed

        // custom handling to create new locations
        // SubmissionController::handleLocationData($submission);

    }

    public static function processHouseholdSubmission(Submission $submission): void
    {
        $farmDataEntity = $submission->rootEntity;

        // custom handling to fill in 12 months irrigation percentage in farm_survey_data record
        $farmDataEntity->addValues(SubmissionController::getIrrigationData($submission));

        // create records from nested repeat groups for seasonal_worker_seasons

        $seasonalWorkersDataset = Dataset::firstWhere('name', 'Seasonal Workers in a Season');
        $farmDataEntity->addChildEntities(SubmissionController::handleSeasonalWorkersData($submission), $seasonalWorkersDataset);

        $farmDataEntity->addChildEntities(SubmissionController::handleSeasonalLaboursData($submission), $seasonalWorkersDataset);

        $farmProductsDataset = Dataset::firstWhere('name', 'Products');
        $farmDataEntity->addChildEntities(SubmissionController::handleProductsData($submission), $farmProductsDataset);
        $farmDataEntity->addChildEntities(SubmissionController::handleOtherProductData($submission), $farmProductsDataset);

    }

    public static function processFieldworkSubmission(Submission $submission): void
    {
        //
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
        $irrigationValues = new Collection();

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
        if ($irrigationPercentage == null || $irrigationPercentage == "null") {
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


    // ******************** //


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
            ->map(function (array $otherProduct) use ($submission): ?array {

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


    public static function prepareNewRecordData($items, $columnNames): array
    {
        $result = [];

        foreach ($items as $key => $value) {
            if (in_array($key, $columnNames)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }


}
