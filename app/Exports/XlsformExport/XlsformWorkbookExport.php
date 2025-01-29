<?php

namespace App\Exports\XlsformExport;

use App\Models\Xlsforms\Xlsform;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


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
