<?php

namespace App\Exports;

use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CustomIndicatorSurveySheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected Team $team;
    protected string $surveyType;

    public function __construct($team, $surveyType)
    {
        $this->team = $team;
        $this->surveyType = $surveyType;
    }

    public function collection(): Enumerable|Collection
    {
        return collect(
            $this->team->localIndicators()
                ->where('is_custom', 1)
                ->where('survey', $this->surveyType)
                ->get()
            )->map(function ($indicator) {
                return [
                    'indicator_id' => $indicator->id,
                    'indicator' => $indicator->name,
                ];
        });
    }

    public function title(): string
    {
        return 'survey';
    }

    public function headings(): array
    {
        $locales = $this->team->locales()->with('language')->get();

        $headings = [
            'indicator ID',
            'indicator',
            'type',
            'name',
        ];

        foreach ($locales as $locale) {
            $headings[] = "label::$locale->odk_label";
            $headings[] = "hint::$locale->odk_label";
        }

        $headings = array_merge($headings, [
            'required',
        ]);

        foreach ($locales as $locale) {
            $headings[] = "required_message::$locale->odk_label";
        }

        $headings = array_merge($headings, [
            'calculation',
            'relevant',
            'appearance',
            'constraint',
        ]);

        foreach ($locales as $locale) {
            $headings[] = "constraint_message::$locale->odk_label";
        }

        $headings = array_merge($headings, [
            'choice_filter',
            'repeat_count',
            'default',
            'note',
            'trigger',
        ]);

        foreach ($locales as $locale) {
            $headings[] = "media::image::$locale->odk_label";
        }

        return $headings;
    }

    public function styles(Worksheet $sheet): array
    {

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
