<?php

namespace App\Imports;

use App\Models\Xlsforms\Xlsform;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class CustomIndicatorWorkbookImport  implements WithMultipleSheets, ShouldQueue, WithChunkReading
{

    use RegistersEventListeners;
    use Importable;

    public function __construct(public Xlsform $xlsform, public Collection $translatableHeadings)
    {
    }

    public function sheets(): array
    {
        return [
            'survey' => new CustomIndicatorSurveyImport($this->xlsform, $this->translatableHeadings['survey']),
            'choices' => new CustomIndicatorChoicesImport($this->xlsform, $this->translatableHeadings['choices']),
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

}
