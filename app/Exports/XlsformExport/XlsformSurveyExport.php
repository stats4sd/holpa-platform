<?php

namespace App\Exports\XlsformExport;

use App\Models\Locale;
use App\Models\Team;
use App\Models\Xlsforms\Xlsform;
use App\Models\XlsformTemplateLanguage;
use App\Models\XlsformTemplates\SurveyRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;

class XlsformSurveyExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnWidths
{
    public Collection $surveyRows;
    public Collection $dynamicStylesRowLists;



    public function __construct(public Xlsform $xlsform, public Collection $xlsformTemplateLanguages, public Collection $languageStringTypes)
    {
        $this->surveyRows = $this->xlsform
            ->xlsformTemplate
            ->surveyRows
            ->sortBy('id')
            ->load('languageStrings')
            ->map(function (SurveyRow $row) {
                return collect([
                    'id' => $row->id,
                    'type' => $row->type,
                    'name' => $row->name,
                    ...$this->getLanguageStrings($row, 'label'),
                    ...$this->getLanguageStrings($row, 'hint'),
                    'required' => $row->required,
                    ...$this->getLanguageStrings($row, 'required_message'),
                    'calculation' => $row->calculation,
                    'relevant' => $row->relevant,
                    ...$this->getLanguageStrings($row, 'relevant_message'),
                    'appearance' => $row->appearance,
                    'constraint' => $row->constraint,
                    ...$this->getLanguageStrings($row, 'constraint_message'),
                    'choice_filter' => $row->choice_filter,
                    'repeat_count' => $row->repeat_count,
                    ...$this->getLanguageStrings($row, 'mediaimage'),
                    'default' => $row->default,
                ]);
            });

        $this->dynamicStylesRowLists = $this->getDynamicStylesRowLists($this->surveyRows);

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return $this->surveyRows;
    }

    public function headings(): array
    {
        return $this->surveyRows->first()->keys()->toArray();
    }

    public function title(): string
    {
        return 'survey';
    }

    private function getLanguageStrings(mixed $row, string $string): Collection
    {
        return $this->xlsformTemplateLanguages
            ->mapWithKeys(function (XlsformTemplateLanguage $xlsformTemplateLanguage) use ($row, $string) {

                // fix for mediaimage needing to be media::image, etc.
                $outputString = $string;

                if($string === 'mediaimage') {
                    $outputString = 'media::image';
                }

                if($string === 'mediaaudio') {
                    $outputString = 'media::audio';
                }

                if($string === 'mediavideo') {
                    $outputString = 'media::video';
                }

                $key = "$outputString::{$xlsformTemplateLanguage->language->name} ({$xlsformTemplateLanguage->language->iso_alpha2})";
                $value = $row->languageStrings
                    ->where('language_string_type_id', $this->languageStringTypes->where('name', $string)->first()->id)
                    ->where('xlsform_template_language_id', $xlsformTemplateLanguage->id)
                    ->first()?->text ?? '';

                return [$key => $value];
            });
    }


    public function columnWidths(): array
    {
        $languageCount = $this->xlsformTemplateLanguages->count();

        $labelColumns = $this->xlsformTemplateLanguages->mapWithKeys(fn(XlsformTemplateLanguage $language, $index) => [chr(67 + $index) => 45]);
        $hintColumns = $this->xlsformTemplateLanguages->mapWithKeys(fn(XlsformTemplateLanguage $language, $index) => [chr(67 + $languageCount + $index) => 45]);

        return [
            'A' => 20,
            'B' => 30,
            ...$labelColumns->toArray(),
            ...$hintColumns->toArray(),
            chr(67 + $languageCount * 2) => 15,
            chr(67 + $languageCount * 2 + 1) => 15,
            chr(67 + $languageCount * 2 + 2) => 15,
            chr(67 + $languageCount * 2 + 3) => 15,
            chr(67 + $languageCount * 2 + 4) => 15,
            chr(67 + $languageCount * 2 + 5) => 15,
            chr(67 + $languageCount * 2 + 6) => 15,
            chr(67 + $languageCount * 2 + 7) => 15,
            chr(67 + $languageCount * 2 + 8) => 15,
            chr(67 + $languageCount * 2 + 9) => 15,
            chr(67 + $languageCount * 2 + 10) => 15,
            chr(67 + $languageCount * 2 + 11) => 15,
            chr(67 + $languageCount * 2 + 12) => 15,
            chr(67 + $languageCount * 2 + 13) => 15,
            chr(67 + $languageCount * 2 + 14) => 15,
            chr(67 + $languageCount * 2 + 15) => 15,
            chr(67 + $languageCount * 2 + 16) => 15,
            chr(67 + $languageCount * 2 + 17) => 15,
            chr(67 + $languageCount * 2 + 18) => 15,
            chr(67 + $languageCount * 2 + 19) => 15,
            chr(67 + $languageCount * 2 + 20) => 15,
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        $languageCount = $this->xlsformTemplateLanguages->count();

        $wrapStyle = ['alignment' => ['wrapText' => true]];

        $beginGroupStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '8ED873',],
                'endColor' => ['rgb' => '8ED873',],
            ],
        ];

        $endGroupStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FA8E78',],
                'endColor' => ['rgb' => 'FA8E78',],
            ],
        ];

        $beginRepeatStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '83CAEB',],
                'endColor' => ['rgb' => '83CAEB',],
            ],
        ];

        $endRepeatStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E49EDD',],
                'endColor' => ['rgb' => 'E49EDD',],
            ],
        ];


        // starting at C, make label + hint columns auto-wrap per Xlsformtemplatelangauge
        $wrapLabelList = $this->xlsformTemplateLanguages->map(fn(XlsformTemplateLanguage $language, $index) => chr(67 + $index));
        $wrapHintList = $this->xlsformTemplateLanguages->map(fn(XlsformTemplateLanguage $language, $index) => chr(67 + $languageCount + $index));


        // **** APPLY STYLES ****

        $sheet->getStyle('1:1')->getFont()->setBold(true);

        foreach($wrapLabelList as $column) {
            $sheet->getStyle($column . ':' . $column)->applyFromArray($wrapStyle);
        }

        foreach($wrapHintList as $column) {
            $sheet->getStyle($column . ':' . $column)->applyFromArray($wrapStyle);
        }

        foreach($this->dynamicStylesRowLists['beginGroupRows'] as $row) {
            $sheet->getStyle($row . ':' . $row)->applyFromArray($beginGroupStyle);
        }

        foreach($this->dynamicStylesRowLists['endGroupRows'] as $row) {
            $sheet->getStyle($row . ':' . $row)->applyFromArray($endGroupStyle);
        }

        foreach($this->dynamicStylesRowLists['beginRepeatRows'] as $row) {
            $sheet->getStyle($row . ':' . $row)->applyFromArray($beginRepeatStyle);
        }

        foreach($this->dynamicStylesRowLists['endRepeatRows'] as $row) {
            $sheet->getStyle($row . ':' . $row)->applyFromArray($endRepeatStyle);
        }

    }

    private function getDynamicStylesRowLists(Collection $surveyRows): Collection
    {
        $beginGroupRows = $surveyRows->filter(fn(Collection $surveyRow) => $surveyRow['type'] === 'begin_group')->pluck('id');
        $endGroupRows = $surveyRows->filter(fn(Collection $surveyRow) => $surveyRow['type'] === 'end_group')->pluck('id');
        $beginRepeatRows = $surveyRows->filter(fn(Collection $surveyRow) => $surveyRow['type'] === 'begin_repeat')->pluck('id');
        $endRepeatRows = $surveyRows->filter(fn(Collection $surveyRow) => $surveyRow['type'] === 'end_repeat')->pluck('id');

        return collect([
            'beginGroupRows' => $beginGroupRows->map(fn($id) => $id + 1),
            'endGroupRows' => $endGroupRows->map(fn($id) => $id + 1),
            'beginRepeatRows' => $beginRepeatRows->map(fn($id) => $id + 1),
            'endRepeatRows' => $endRepeatRows->map(fn($id) => $id + 1),
        ]);
    }
}
