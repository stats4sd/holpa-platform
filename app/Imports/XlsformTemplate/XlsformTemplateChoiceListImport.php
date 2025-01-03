<?php

namespace App\Imports\XlsformTemplate;

use App\Models\Interfaces\WithXlsformFile;
use App\Models\Xlsforms\ChoiceList;
use Illuminate\Contracts\Queue\ShouldQueue;
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

    public function __construct(public WithXlsformFile $xlsformTemplate)
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

        if (isset($row['localisable'])) {
            $localisable = match ($row['localisable']) {
                'true', 'yes', 'TRUE', 'YES', 'Yes', 'True', '1', 1, true => true,
                default => false,
            };
        }
        else {
            $localisable = false;
        }

        return new ChoiceList([
            'template_id' => $this->xlsformTemplate->id,
            'template_type' => get_class($this->xlsformTemplate),
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
        return ['template_id', 'template_type', 'list_name'];
    }
}
