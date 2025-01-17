<?php

namespace App\Exports\XlsformExport;

use App\Models\Team;
use App\Models\XlsformLanguages\Language;
use App\Models\XlsformLanguages\Locale;
use App\Models\Xlsforms\SurveyRow;
use App\Models\Xlsforms\Xlsform;
use App\Models\Xlsforms\XlsformModule;
use App\Models\Xlsforms\XlsformModuleVersion;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithXlsforms;

class XlsformSurveyExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnWidths
{
    /** @var Collection<Locale> */
    public Collection $locales;

    /** @var Collection<Collection> */
    public Collection $rows;

    /** @var Collection<Collection> */
    public Collection $dynamicStylesRowLists;

    public function __construct(public Xlsform $xlsform)
    {
        $this->locales = $xlsform->owner->locales;

        // Get list of XlsformModuleVersions to use
        /** @var Collection<XlsformModuleVersion> $xlsformTemplate */
        $xlsformModuleVersions = $this->xlsform->xlsformTemplate->xlsformModules()
            ->with('xlsformModuleVersions')
            ->orderBy('xlsform_modules.id') // probably in the future we'll have a separate way of re-ordering the modules
            ->get()
            ->map(function (XlsformModule $module) {

                if ($module->name === 'diet_quality') {

                    // find out which diet diversity module the team has chosen.
                    /** @var Team $team */
                    $team = $this->xlsform->owner;

                    if ($team->dietDiversityModuleVersion) {
                        return $team->dietDiversityModuleVersion;
                    }
                }

                // by default, return the (one and only) 'default' version of the module
                return $module->defaultXlsformVersion;

            });

        $surveyRows = $xlsformModuleVersions->map(function (XlsformModuleVersion $xlsformModuleVersion) {
            return $xlsformModuleVersion
                ->surveyRows
                ->sortBy('id')
                ->load('languageStrings');
        })->flatten(1);

        $propertyHeadings = $this->getHeadingsFromProperties($surveyRows);

        $this->rows = $surveyRows
            ->map(function (SurveyRow $row) use ($propertyHeadings) {

                $properties = $propertyHeadings->mapWithKeys(fn(string $heading) => [$heading => $row->properties[Str::replace(':', '', $heading)] ?? null]);

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
                    ...$properties, // includes media items that are not per-language (e.g. "media::image")
                ]);
            });

        $this->dynamicStylesRowLists = $this->getDynamicStylesRowLists($this->rows);

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->rows->first()->keys()->toArray();
    }

    public function title(): string
    {
        return 'survey';
    }

    private function getLanguageStrings(SurveyRow $row, string $string): Collection
    {
        return $this->locales
            ->mapWithKeys(function (Locale $locale) use ($row, $string) {
                $outputString = $this->expandMediaColumnHeaders($string);

                $key = "$outputString::{$locale->language->name} ({$locale->language->iso_alpha2})";
                $value = $row->languageStrings()
                    ->whereHas('languageStringType', fn($query) => $query->where('name', $string))
                    ->first()?->text ?? '';

                return [$key => $value];
            });
    }

    private function getHeadingsFromProperties(Collection $surveyRows): Collection
    {
        return $surveyRows
            ->map(fn($surveyRow) => $surveyRow
                ->properties
                ?->mapWithKeys(fn(string $value, string $key) => [$this->expandMediaColumnHeaders($key) => $value])
                ->keys())
            ->filter()
            ->flatten()
            ->unique();
    }

    public function columnWidths(): array
    {
        $languageCount = $this->locales->count();

        $labelColumns = $this->locales->mapWithKeys(fn(Locale $locale, $index) => [chr(67 + $index) => 45]);
        $hintColumns = $this->locales->mapWithKeys(fn(Locale $locale, $index) => [chr(67 + $languageCount + $index) => 45]);

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
        $languageCount = $this->locales->count();

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
        $wrapLabelList = $this->locales->map(fn(Locale $language, $index) => chr(67 + $index));
        $wrapHintList = $this->locales->map(fn(Locale $language, $index) => chr(67 + $languageCount + $index));


        // **** APPLY STYLES ****

        $sheet->getStyle('1:1')->getFont()->setBold(true);

        foreach ($wrapLabelList as $column) {
            $sheet->getStyle($column . ':' . $column)->applyFromArray($wrapStyle);
        }

        foreach ($wrapHintList as $column) {
            $sheet->getStyle($column . ':' . $column)->applyFromArray($wrapStyle);
        }

        foreach ($this->dynamicStylesRowLists['beginGroupRows'] as $row) {
            $sheet->getStyle($row . ':' . $row)->applyFromArray($beginGroupStyle);
        }

        foreach ($this->dynamicStylesRowLists['endGroupRows'] as $row) {
            $sheet->getStyle($row . ':' . $row)->applyFromArray($endGroupStyle);
        }

        foreach ($this->dynamicStylesRowLists['beginRepeatRows'] as $row) {
            $sheet->getStyle($row . ':' . $row)->applyFromArray($beginRepeatStyle);
        }

        foreach ($this->dynamicStylesRowLists['endRepeatRows'] as $row) {
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

    /**
     * @param string $string
     * @return string
     */
    function expandMediaColumnHeaders(string $string): string
    {
        // fix for mediaimage needing to be media::image, etc.

        if ($string === 'mediaimage') {
            return 'media::image';
        }

        if ($string === 'mediaaudio') {
            return 'media::audio';
        }

        if ($string === 'mediavideo') {
            return 'media::video';
        }

        return $string;
    }
}
