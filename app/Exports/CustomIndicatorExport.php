<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomIndicatorExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $team;

    public function __construct($team)
    {
        $this->team = $team;
    }

    public function collection()
    {
        return collect($this->team->localIndicators()->where('is_custom', 1)->get())->map(function ($indicator) {
            return [
                'indicator_id' => $indicator->id,
                'indicator' => $indicator->name,
            ];
        });
    }

    public function title(): string
    {
        return 'custom indicators';
    }

    public function headings(): array
    {
        return [
            'Indicator ID',
            'Indicator',
            'Survey component'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $wrap = [
            'alignment' => [
                'wrapText' => true
            ],
        ];

        $h1 = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '5FA35A'],
            ],
        ];

        return [
            1 => $h1
        ];
    }
}
