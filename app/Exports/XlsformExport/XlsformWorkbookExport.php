<?php

namespace App\Exports\XlsformExport;

use App\Models\Team;
use App\Models\XlsformLanguages\LanguageStringType;
use App\Models\XlsformLanguages\Locale;
use App\Models\Xlsforms\Xlsform;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithXlsforms;


class XlsformWorkbookExport implements WithMultipleSheets
{

    public function __construct(public Xlsform $xlsform)
    {
    }

    public function sheets(): array
    {
        return [
            new XlsformSurveyExport($this->xlsform),
            new XlsformChoicesExport($this->xlsform),
            new XlsformSettingsExport($this->xlsform),
        ];

    }
}
