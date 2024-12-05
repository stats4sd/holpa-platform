<?php

namespace App\Imports\XlsformTemplate;

use App\Models\XlsformTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class XlsformTemplateWorkbookLanguageStringImport implements WithMultipleSheets, ShouldQueue, WithChunkReading
{

    use Importable;


    public function __construct(public XlsformTemplate $xlsformTemplate, public string $heading)
    {
    }

    // Specify the "survey" sheet
    public function sheets(): array
    {
        return [
            'survey' => new SurveyLanguageStringImport($this->xlsformTemplate, $this->heading),
            //'choices' => new ChoicesLanguageStringImport($this->xlsformTemplate, $this->heading),
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    }
