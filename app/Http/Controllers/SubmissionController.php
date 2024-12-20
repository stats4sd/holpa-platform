<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SurveyData\Crop;
use App\Models\SampleFrame\Farm;
use App\Models\SurveyData\FishUse;
use App\Models\SurveyData\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SampleFrame\Location;
use Illuminate\Support\Facades\Schema;
use App\Models\SurveyData\LivestockUse;
use App\Models\SurveyData\FarmSurveyData;
use App\Models\SurveyData\SeasonalWorkerSeason;
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

        // suppose there should be only one farm_survey_data for a submission id
        $farmSurveyData = FarmSurveyData::where('submission_id', $submission->id)->first();


        // custom handling to fill in 12 months irrigation percentage in farm_survey_data record
        SubmissionController::handleIrrigationData($submission, $farmSurveyData);


        // nested repeat groups data handling can be generalised.
        // I would expect to include this feature in core ODK handling package in the future


        // create records from nested repeat groups for seasonal_worker_seasons
        SubmissionController::handleSeasonalWorkersData($submission, $farmSurveyData);
        SubmissionController::handleSeasonalLaboursData($submission, $farmSurveyData);


        // create records from nested repeat groups for livestock_uses
        SubmissionController::handleLivestockUsesData($submission, $farmSurveyData);


        // create records from nested repeat groups for fish_uses
        SubmissionController::handleFishUsesData($submission, $farmSurveyData);


        // custom handling for products data
        SubmissionController::handleProductsData($submission, $farmSurveyData);



        // update farm_survey_data_id in repeat groups tables
        SubmissionController::updateFarmSurveyDataId($submission, $farmSurveyData);


        // TODO: submissions table, fill in values to columns started_at, ended_at, survey_duration


        // TODO: farms table, update column household_form_completed, fieldwork_form_completed


        // custom handling to create new locations
        // SubmissionController::handleLocationData($submission);


    }


    // ******************** //


    // custom handling for irrigation data
    // to fill in irrigation percentage for 12 months in farm_survey_data record
    public static function handleIrrigationData(Submission $submission, FarmSurveyData $farmSurveyData): void
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
            // ray('irrigation_percentage is null, do nothing');
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


    // ******************** //


    // custom handling for seasonal_worker_seasons data
    // create records from nested repeat groups for seasonal_worker_seasons
    // /survey/income/labour/household_mm_labour/seasonal_workers/seasonal_workers_s
    public static function handleSeasonalWorkersData(Submission $submission, FarmSurveyData $farmSurveyData): void
    {
        // ray('SubmissionController.handleSeasonalWorkersData() starts...');

        // existence check for nested repeat group data in submission content
        if (isset($submission->content['survey']['income']['labour']['household_mm_labour']['seasonal_workers'])) {
            // ray('seasonal_workers data found');

            $class = \App\Models\SurveyData\SeasonalWorkerSeason::class;
            $model = new $class;
            $columnNames = Schema::getColumnListing($model->getTable());
            // ray($columnNames);

            $seasonalWorkers = $submission->content['survey']['income']['labour']['household_mm_labour']['seasonal_workers'];

            foreach ($seasonalWorkers as $seasonalWorker) {

                if (isset($seasonalWorker['seasonal_workers_s'])) {
                    // ray('seasonal_workers_s data found');

                    // get data from submission JSON content
                    $seasonalWorkersItems = $seasonalWorker['seasonal_workers_s'];

                    // handle nested repeat groups one by one
                    foreach ($seasonalWorkersItems as $seasonalWorkersItem) {
                        // ray($seasonalWorkersItem);

                        $result = SubmissionController::prepareNewRecordData($seasonalWorkersItem, $columnNames);

                        // assumes data under "/survey/income/labour/household_mm_labour" are household members
                        $result['household_members'] = 1;

                        $result['submission_id'] = $submission->id;
                        $result['farm_survey_data_id'] = $farmSurveyData->id;

                        // ray($result);

                        $seasonalWorkerSeason = SeasonalWorkerSeason::create($result);
                    }
                } else {
                    // ray('seasonal_workers_s data not found');
                }
            }
        } else {
            // ray('seasonal_workers data not found');
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


    // ******************** //


    // custom handling for seasonal_worker_seasons data
    // create records from nested repeat groups for seasonal_worker_seasons
    // /survey/income/labour/labourers/sesaonal_labourers/seasonal_labourers_s/
    public static function handleSeasonalLaboursData(Submission $submission, FarmSurveyData $farmSurveyData): void
    {
        // ray('SubmissionController.handleSeasonalLaboursData() starts...');

        // existence check for nested repeat group data in submission content
        if (isset($submission->content['survey']['income']['labour']['labourers']['sesaonal_labourers'])) {
            // ray('sesaonal_labourers data found');

            $class = \App\Models\SurveyData\SeasonalWorkerSeason::class;
            $model = new $class;
            $columnNames = Schema::getColumnListing($model->getTable());
            // ray($columnNames);

            $seasonalLabours = $submission->content['survey']['income']['labour']['labourers']['sesaonal_labourers'];

            foreach ($seasonalLabours as $seasonalLabour) {

                if (isset($seasonalLabour['seasonal_labourers_s'])) {
                    // ray('seasonal_labourers_s data found');

                    // get data from submission JSON content
                    $seasonalLaboursItems = $seasonalLabour['seasonal_labourers_s'];

                    // handle nested repeat groups one by one
                    foreach ($seasonalLaboursItems as $seasonalLaboursItem) {
                        // ray($seasonalLaboursItem);

                        $result = SubmissionController::prepareNewRecordData($seasonalLaboursItem, $columnNames);

                        // assumes data under "/survey/income/labour/labourers" are not household members
                        $result['household_members'] = 0;

                        $result['submission_id'] = $submission->id;
                        $result['farm_survey_data_id'] = $farmSurveyData->id;

                        // ray($result);

                        $seasonalWorkerSeason = SeasonalWorkerSeason::create($result);
                    }
                } else {
                    // ray('seasonal_labourers_s data not found');
                }
            }
        } else {
            // ray('sesaonal_labourers data not found');
        }
    }


    // ******************** //


    // custom handling for livestock_uses data
    // create records from nested repeat groups for livestock_uses
    // /survey/income/livestock_production/primary_livestock_details/primary_livestock_uses
    public static function handleLivestockUsesData(Submission $submission, FarmSurveyData $farmSurveyData): void
    {
        // ray('SubmissionController.handleLivestockUsesData() starts...');

        // existence check for nested repeat group data in submission content
        if (isset($submission->content['survey']['income']['livestock_production']['primary_livestock_details'])) {
            // ray('primary_livestock_details data found');

            $class = \App\Models\SurveyData\LivestockUse::class;
            $model = new $class;
            $columnNames = Schema::getColumnListing($model->getTable());
            // ray($columnNames);

            $livestockProductions = $submission->content['survey']['income']['livestock_production']['primary_livestock_details'];

            foreach ($livestockProductions as $livestockProduction) {

                if (isset($livestockProduction['primary_livestock_uses'])) {
                    // ray('primary_livestock_uses data found');

                    // get data from submission JSON content
                    $primaryLivestockUses = $livestockProduction['primary_livestock_uses'];

                    // handle nested repeat groups one by one
                    foreach ($primaryLivestockUses as $primaryLivestockUse) {
                        // ray($primaryLivestockUse);

                        $result = SubmissionController::prepareNewRecordData($primaryLivestockUse, $columnNames);

                        $result['submission_id'] = $submission->id;
                        $result['farm_survey_data_id'] = $farmSurveyData->id;

                        // ray($result);

                        $livestockhUse = LivestockUse::create($result);
                    }
                } else {
                    // ray('primary_livestock_uses data not found');
                }
            }
        } else {
            // ray('primary_livestock_details data not found');
        }
    }


    // ******************** //


    // custom handling for fish_uses data
    // create records from nested repeat groups for fish_uses
    // /survey/income/fish_production/fish_repeat/fish_production_repeat/
    public static function handleFishUsesData(Submission $submission, FarmSurveyData $farmSurveyData): void
    {
        // ray('SubmissionController.handleFishUsesData() starts...');

        // existence check for nested repeat group data in submission content
        if (isset($submission->content['survey']['income']['fish_production']['fish_repeat'])) {
            // ray('fish_repeat data found');

            $class = \App\Models\SurveyData\FishUse::class;
            $model = new $class;
            $columnNames = Schema::getColumnListing($model->getTable());
            // ray($columnNames);

            $fishRepeats = $submission->content['survey']['income']['fish_production']['fish_repeat'];

            foreach ($fishRepeats as $fishRepeat) {

                if (isset($fishRepeat['fish_production_repeat'])) {
                    // ray('fish_production_repeat data found');

                    // get data from submission JSON content
                    $fishProductionRepeats = $fishRepeat['fish_production_repeat'];

                    // handle nested repeat groups one by one
                    foreach ($fishProductionRepeats as $fishProductionRepeat) {
                        // ray($fishProductionRepeat);

                        $result = SubmissionController::prepareNewRecordData($fishProductionRepeat, $columnNames);

                        $result['submission_id'] = $submission->id;
                        $result['farm_survey_data_id'] = $farmSurveyData->id;

                        // ray($result);

                        $fishUse = FishUse::create($result);
                    }
                } else {
                    // ray('fish_production_repeat data not found');
                }
            }
        } else {
            // ray('fish_repeat data not found');
        }
    }


    // ******************** //


    // custom handling for products data
    public static function handleProductsData(Submission $submission, FarmSurveyData $farmSurveyData): void
    {
        // ray('SubmissionController.handleProductsData() starts...');

        // get farm_products
        if (isset($submission->content['survey']['income']['farm_characteristics']['farm_products'])) {
            // ray('farm_products data found');

            $farmProductsList = $submission->content['survey']['income']['farm_characteristics']['farm_products'];
            // ray($farmProductsList);

            // split farm products, handle them one by one
            $farmProducts = str_getcsv($farmProductsList, ' ');
            // ray($farmProducts);

            foreach ($farmProducts as $farmProduct) {
                $prefix = $farmProduct;

                // special handling for "crops", change it to "crop" as prefix
                if ($farmProduct == 'other') {
                    $prefix = 'other_prod';
                }

                if ($farmProduct != 'other') {
                    SubmissionController::prepareProductData($submission, $farmSurveyData, $prefix);
                } else {
                    SubmissionController::prepareOtherProductData($submission, $farmSurveyData, $prefix);
                }
            }
        } else {
            // ray('farm_products data not found');
        }
    }


    // prepare data for crops,livestock, fish, trees, honey
    public static function prepareProductData($submission, $farmSurveyData, $prefix): void
    {
        // use hardcoded product names temporary
        $productNames = [
            'crops' => 'Crops (including perennial crops)',
            'livestock' => 'Livestock',
            'fish' => 'Fish',
            'trees' => 'Trees (e.g., for wood, bark, rubber)',
            'honey' => 'Honey',
        ];

        $useItemNames = [
            'hh_consumption',
            'cooking',
            'building',
            'heating',
            'hh_other_use',
            'livestock_consumption',
            'on_farm_use',
            'sales',
            'gifts',
            'waster',
            'other_use',
            'other_use_specify',
        ];

        $salesBuyerItemNames = [
            'buyer',
            'buyer_other',
            'fair_price',
        ];

        $result = [];

        // special handling for "crops", change it from "crops" to "crop" before handling submission content
        if ($prefix == 'crops') {
            $prefix = 'crop';
        }

        $productUseData = $submission->content['survey']['income']['farm_characteristics'][$prefix . '_use'];
        // ray($productUseData);

        foreach ($useItemNames as $useItemName) {
            if (isset($productUseData[$prefix . '_' . $useItemName])) {
                $result[$useItemName] = $productUseData[$prefix . '_' . $useItemName];
            }
        }

        $productSalesBuyerData = $submission->content['survey']['income']['farm_characteristics'][$prefix . '_sales_buyers'];
        // ray($productSalesBuyerData);

        foreach ($salesBuyerItemNames as $salesBuyerItemName) {
            if (isset($productSalesBuyerData[$prefix . '_' . $salesBuyerItemName])) {
                $result[$salesBuyerItemName] = $productSalesBuyerData[$prefix . '_' . $salesBuyerItemName];
            }
        }

        // special handling for "crops", change it from "crop" to "crops" after handling submission content
        if ($prefix == 'crop') {
            $prefix = 'crops';
        }

        $result['product_id'] = $prefix;
        $result['product_name'] = $productNames[$prefix];
        $result['submission_id'] = $submission->id;
        $result['farm_survey_data_id'] = $farmSurveyData->id;

        // ray($result);

        // create products record
        $product = Product::create($result);
    }


    // prepare data for other
    public static function prepareOtherProductData($submission, $farmSurveyData, $prefix): void
    {
        // ray('SubmissionController.prepareOtherProductData() starts...');

        $useSalesItemNames = [
            'hh_consumption',
            'cooking',
            'building',
            'heating',
            'hh_other_use',
            'livestock_consumption',
            'on_farm_use',
            'sales',
            'gifts',
            'waster',
            'other_use',
            'other_use_specify',
            'buyer',
            'buyer_other',
            'fair_price',
        ];

        $result = [];

        $otherProductRepeats = $submission->content['survey']['income']['farm_characteristics']['other_product_use_sales'];
        // ray($otherProductRepeats);

        foreach ($otherProductRepeats as $otherProductRepeat) {
            // ray($otherProductRepeat);

            foreach ($useSalesItemNames as $useSalesItemName) {
                if (isset($otherProductRepeat[$prefix . '_' . $useSalesItemName])) {
                    $result[$useSalesItemName] = $otherProductRepeat[$prefix . '_' . $useSalesItemName];
                }
            }

            $result['product_name'] = $otherProductRepeat[$prefix . '_name'];
            $result['submission_id'] = $submission->id;
            $result['farm_survey_data_id'] = $farmSurveyData->id;

            // ray($result);

            // create products record
            $product = Product::create($result);
        }
    }

    // ******************** //

    // update farm_survey_data_id in repeat groups tables
    public static function updateFarmSurveyDataId(Submission $submission, FarmSurveyData $farmSurveyData): void
    {
        // update farm_survey_data_id in repeat group records that are created by filament-odk-link package
        // fish_uses, livestock_uses, seasonal_worker_seasons, products records are created by custom handling, which fill in farm_survey_data_id already
        DB::table('crops')->where('submission_id', $submission->id)->update(['farm_survey_data_id' => $farmSurveyData->id]);
        DB::table('ecological_practices')->where('submission_id', $submission->id)->update(['farm_survey_data_id' => $farmSurveyData->id]);
        DB::table('fishes')->where('submission_id', $submission->id)->update(['farm_survey_data_id' => $farmSurveyData->id]);
        DB::table('livestocks')->where('submission_id', $submission->id)->update(['farm_survey_data_id' => $farmSurveyData->id]);
        DB::table('permanent_workers')->where('submission_id', $submission->id)->update(['farm_survey_data_id' => $farmSurveyData->id]);
    }


    // ******************** //


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
