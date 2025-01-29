<?php

namespace App\Exports\DataExport;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FarmSurveyDataExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new DataDictionaryIndicatorExport();
        $sheets[] = new DataDictionaryDataExport();
        $sheets[] = new CodebookEntryExport();

        // $sheets[] = new CalculatedIndicatorExport();

        // farm survey data
//        $sheets[] = new FarmSurveyDataSheetExport();
//
//        // repeat groups
//        $sheets[] = new RepeatGroupExport(model: new Crop());
//        $sheets[] = new RepeatGroupExport(model: new EcologicalPractice());
//        $sheets[] = new RepeatGroupExport(model: new FieldworkSite());
//        $sheets[] = new RepeatGroupExport(model: new Fish());
//        $sheets[] = new RepeatGroupExport(model: new FishUse());
//        $sheets[] = new RepeatGroupExport(model: new Livestock());
//        $sheets[] = new RepeatGroupExport(model: new LivestockUse());
//        $sheets[] = new RepeatGroupExport(model: new PermanentWorker());
//        $sheets[] = new RepeatGroupExport(model: new Product());
//        $sheets[] = new RepeatGroupExport(model: new SeasonalWorkerSeason());

        return $sheets;
    }
}
