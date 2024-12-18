<?php

namespace App\Exports\DataExport;

use App\Models\SurveyData\Crop;
use App\Models\SurveyData\EcologicalPractice;
use App\Models\SurveyData\FieldworkSite;
use App\Models\SurveyData\Fish;
use App\Models\SurveyData\FishUse;
use App\Models\SurveyData\Livestock;
use App\Models\SurveyData\LivestockUse;
use App\Models\SurveyData\PermanentWorker;
use App\Models\SurveyData\Product;
use App\Models\SurveyData\SeasonalWorkerSeason;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FarmSurveyDataExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new DataDictionaryExport();
        $sheets[] = new ChoiceListExport();

        // $sheets[] = new CalculatedIndicatorExport();

        // farm survey data
        $sheets[] = new FarmSurveyDataSheetExport();

        // repeat groups
        $sheets[] = new RepeatGroupExport(model: new Crop());
        $sheets[] = new RepeatGroupExport(model: new EcologicalPractice());
        $sheets[] = new RepeatGroupExport(model: new FieldworkSite());
        $sheets[] = new RepeatGroupExport(model: new Fish());
        $sheets[] = new RepeatGroupExport(model: new FishUse());
        $sheets[] = new RepeatGroupExport(model: new Livestock());
        $sheets[] = new RepeatGroupExport(model: new LivestockUse());
        $sheets[] = new RepeatGroupExport(model: new PermanentWorker());
        $sheets[] = new RepeatGroupExport(model: new Product());
        $sheets[] = new RepeatGroupExport(model: new SeasonalWorkerSeason());

        return $sheets;
    }
}
