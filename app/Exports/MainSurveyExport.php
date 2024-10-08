<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MainSurveyExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        // $sheets[] = new DataDictionaryExport();
        // $sheets[] = new ChoiceListExport();

        //  $sheets[] = new CalculatedIndicatorExport();
        $sheets[] = new MainSurveySheetExport();

        return $sheets;
    }
}
