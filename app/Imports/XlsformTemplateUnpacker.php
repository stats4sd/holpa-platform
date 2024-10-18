<?php

namespace App\Imports;

use App\Models\XlsformTemplate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class XlsformTemplateUnpacker implements WithMultipleSheets
{
    public function __construct(public XlsformTemplate $xlsformTemplate)
    {
    }

    public function sheets(): array
    {
        return [
            // 'survey' => new XlsformTemplateImport(), - for when we mere with form translations
            'choices' => new XlsformTemplateChoiceListImporter($this->xlsformTemplate),
        ];
    }
}
