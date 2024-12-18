<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CustomIndicatorChoicesSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $team;

    public function __construct($team)
    {
        $this->team = $team;
    }

    public function collection()
    {
        return collect([]);
    }

    public function title(): string
    {
        return 'choices';
    }

    public function headings(): array
    {
        $locales = $this->team->locales()->with('language')->get();

        $headings = [
            'list_name',
            'name',
        ];

        foreach ($locales as $locale) {
            $headings[] = "label::$locale->odk_label";
        }

        $headings = array_merge($headings, [
            'filter',
        ]);

        return $headings;
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
