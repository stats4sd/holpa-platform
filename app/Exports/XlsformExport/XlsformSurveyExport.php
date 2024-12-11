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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;

class XlsformSurveyExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnWidths
{
    public Collection $surveyRows;
    public array $dynamicStyles;


    public function __construct(public Xlsform $xlsform, public Collection $xlsformTemplateLanguages, public Collection $languageStringTypes)
    {
        $this->surveyRows = $this->xlsform
            ->xlsformTemplate
            ->surveyRows
            ->sortBy('id')
            ->load('languageStrings')
            ->map(function (SurveyRow $row) {
                return [
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
                    'default' => $row->default,
                ];
            });

        $beginGroupRows = $this->surveyRows->filter(fn(SurveyRow $surveyRow) => $surveyRow['type'] === 'begin_group');
        $endGroupRows = $this->surveyRows->filter(fn(SurveyRow $surveyRow) => $surveyRow['type'] === 'end_group');
        $beginRepeatRows = $this->surveyRows->filter(fn(SurveyRow $surveyRow) => $surveyRow['type'] === 'begin_repeat');
        $endRepeatRows = $this->surveyRows->filter(fn(SurveyRow $surveyRow) => $surveyRow['type'] === 'end_repeat');

        $this->dynamicStyles = [
            ...$beginGroupRows->mapWithKeys(fn(SurveyRow $surveyRow) => [$surveyRow['id'] + 1 => [])->toArray(),
            'end_group' => $endGroupRows->map(fn(SurveyRow $surveyRow) => $surveyRow['id'])->toArray(),
            'begin_repeat' => $beginRepeatRows->map(fn(SurveyRow $surveyRow) => $surveyRow['id'])->toArray(),
            'end_repeat' => $endRepeatRows->map(fn(SurveyRow $surveyRow) => $surveyRow['id'])->toArray(),
        ];

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
            ->mapWithKeys(function(XlsformTemplateLanguage $xlsformTemplateLanguage) use ($row, $string) {
                    $key = "$string::{$xlsformTemplateLanguage->language->name} ({$xlsformTemplateLanguage->language->iso_alpha2})";
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

    public function styles(Worksheet $sheet): array
    {
        // starting at C, make label + hint columns auto-wrap per Xlsformtemplatelangauge
        $wrapLabelArray = $this->xlsformTemplateLanguages->mapWithKeys(fn(XlsformTemplateLanguage $language, $index) => [chr(67 + $index) => ['alignment' => ['wrapText' => true]]]
        )->toArray();

        $languageCount = $this->xlsformTemplateLanguages->count();

        $wrapHintArray = $this->xlsformTemplateLanguages->mapWithKeys(fn(XlsformTemplateLanguage $language, $index) => [chr(67 + $languageCount + $index) => ['alignment' => ['wrapText' => true]]]
        )->toArray();

        return [
            1 => ['font' => ['bold' => true]],
            ...$wrapLabelArray,
            ...$wrapHintArray,
        ];
    }
}
