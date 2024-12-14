<?php

namespace App\Imports\XlsformTemplate;

use App\Models\XlsformTemplates\ChoiceList;
use App\Models\XlsformTemplates\XlsformTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithUpserts;

class XlsformTemplateChoiceListImport implements ToModel, WithMultipleSheets, WithUpserts, WithChunkReading, ShouldQueue, SkipsEmptyRows, WithHeadingRow
{

    use Importable;

    public function __construct(public XlsformTemplate $xlsformTemplate)
    {
    }

    public function sheets(): array
    {
        return [
            'choices' => $this,
        ];
    }

    public function model(array $row): ChoiceList
    {
        $row = collect($row);

        $localisable = match ($row['localisable']) {
            'true', 'yes', 'TRUE', 'YES', 'Yes', 'True', '1', 1, true => true,
            default => false,
        };

        return new ChoiceList([
            'xlsform_template_id' => $this->xlsformTemplate->id,
            'list_name' => $row['list_name'],
            'is_localisable' => $localisable,
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function uniqueBy(): array
    {
        return ['xlsform_template_id', 'list_name'];
    }
}
