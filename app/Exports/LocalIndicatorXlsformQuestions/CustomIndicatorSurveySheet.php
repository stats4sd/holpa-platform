<?php

namespace App\Exports\LocalIndicatorXlsformQuestions;

use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomIndicatorSurveySheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{

    public function __construct(public Team $team)
    {
    }

    public function collection(): Enumerable|Collection
    {
        return collect();
    }

    public function title(): string
    {
        return 'survey';
    }

    public function headings(): array
    {
        $locales = $this->team->locales()->with('language')->get();

        $headings = [
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
            1 => $h1,
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $count = $this->team->localIndicators()
                    ->whereDoesntHave('globalIndicator')->count();

                $sheet = $event->sheet->getDelegate();

                $indicatorLookupList = new DataValidation();
                $indicatorLookupList->setType(DataValidation::TYPE_LIST);
                $indicatorLookupList->setErrorStyle(DataValidation::STYLE_STOP);
                $indicatorLookupList->setAllowBlank(false);
                $indicatorLookupList->setShowInputMessage(true);
                $indicatorLookupList->setShowErrorMessage(true);
                $indicatorLookupList->setShowDropDown(true);
                $indicatorLookupList->setErrorTitle('Input error');
                $indicatorLookupList->setError('Please select an indicator from the list.');
                $indicatorLookupList->setPromptTitle('Pick an indicator');
                $indicatorLookupList->setFormula1('\'indicators\'!$B$3:$B$' . $count + 3);

                $sheet->setDataValidation('A:A', $indicatorLookupList);
            },
        ];
    }

}
