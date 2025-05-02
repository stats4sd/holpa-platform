<?php

namespace App\Exports\LocalIndicatorXlsformQuestions;

use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;

// TODO: study how to pre-select a value for "Indicator" column
class CustomIndicatorSurveySheet extends DefaultValueBinder implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithStyles, WithTitle
{
    public function __construct(public Team $team) {}

    // TODO: study how to pre-select a value for "Indicator" column
    // reference: https://duncanmcclean.com/select-dropdown-cells-with-laravel-excel
    public function bindValue(Cell $cell, $value)
    {
        if (is_array($value)) {
            $validation = $cell->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setAllowBlank(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"' . collect($value)->join(',') . '"');

            $value = '';
        }

        return parent::bindValue($cell, $value);
    }


    public function collection(): Enumerable|Collection
    {
        // find related xlsform module version Ids
        $xlsformModuleVersionIds = $this->team->localIndicators->pluck('xlsform_module_version_id')->toArray();

        // TODO: get related columns only

        // indicator	type	name	label::English (en)	hint::English (en)	required	required_message::English (en)	calculation	relevant
        // appearance	constraint	constraint_message::English (en)	choice_filter	repeat_count	default	note	trigger	media::image::English (en)

        // label::English (en)
        // hint::English (en)
        // required_message::English (en)
        // constraint_message::English (en)
        // media::image::English (en)

        // create an empty collection
        $records = collect();

        // find related custom questions
        $surveyRows = SurveyRow::whereIn('xlsform_module_version_id', $xlsformModuleVersionIds)
            // ->select('type', 'name', 'required', 'calculation', 'relevant', 'appearance', 'constraint', 'choice_filter', 'repeat_count', 'default', 'note', 'trigger')
            ->get();

        // add custom questions to collection one by one
        foreach ($surveyRows as $surveyRow) {
            // TODO: find label
            ray($surveyRow->defaultLabel());

            // TODO: study how to pre-select an option in "Indicator" column
            // TODO: find label, hint, required message, constraint message
            $records->add(['', $surveyRow->type, $surveyRow->name, 'TODO:label']);
        }

        return $records;
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

                $indicatorLookupList = new DataValidation;
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
