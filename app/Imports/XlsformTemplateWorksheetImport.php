<?php

namespace App\Imports;

use App\Models\Language;
use App\Models\LanguageString;
use App\Models\XlsformTemplate;
use App\Models\LanguageStringType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\XlsformTemplateLanguage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class XlsformTemplateWorksheetImport implements WithMultipleSheets
{


    public function __construct(public XlsformTemplate $xlsformTemplate)
    {
    }

    // Specify the "survey" sheet
    public function sheets(): array
    {
        return [
            'survey' => new XlsformTemplateImport($this->xlsformTemplate),
            'choices' => new XlsformTemplateChoicesImport($this->xlsformTemplate),
        ];
    }

}
