<?php

namespace App\Exports\DataExport;

use App\Models\DataDictionaryEntry;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataDictionaryIndicatorExport implements FromQuery, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithColumnWidths, WithStyles
{

    public function __construct()
    {
    }

    public function query(): Builder
    {
        return DataDictionaryEntry::query()
            ->where('for_indicators', 1);
    }

    public function map($row): array
    {
        $data = [];

        foreach ($this->headings() as $heading) {
            $data[$heading] = $row[$heading];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'worksheet',
            'variable',
            'theme',
            'indicator_number',
            'indicator_name',
            'question_or_definition',
            'type',
            'code_list',
        ];
    }

    public function title(): string
    {
        return 'data_dictionary_indicators';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 22,
            'C' => 25,
            'D' => 10,
            'E' => 30,
            'F' => 30,
            'G' => 13,
            'H' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $headingStyle = ['font' => ['bold' => true,],];

        $sheet->getStyle('1:1')->applyFromArray($headingStyle);

    }
}
