<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class XlsformTemplateImport implements ToCollection, WithHeadingRow
{
    private $xlsformTemplateId;

    public function __construct($templateId)
    {
        $this->xlsformTemplateId = $templateId;
    }

    public function collection(Collection $rows)
    {
        // Step 1: Get the headings to extract languages
        $headings = $rows->first()->keys();
        
    }

}