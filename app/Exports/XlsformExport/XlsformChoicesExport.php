<?php

namespace App\Exports\XlsformExport;

use App\Models\Xlsforms\Xlsform;
use App\Models\XlsformTemplateLanguage;
use App\Models\XlsformTemplates\ChoiceListEntry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsformChoicesExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnWidths
{
    /**
     * @var Collection<ChoiceListEntry>
     */
    public Collection $choiceListEntries;

    public function __construct(public Xlsform $xlsform, public Collection $xlsformTemplateLanguages, public Collection $languageStringTypes)
    {

        // get collection in construct so we can use props to get headings
        // simplest case - return all survey rows from the template
        $choiceListEntries = $this->xlsform
            ->xlsformTemplate
            ->choiceListEntries
            ->load(['languageStrings', 'choiceList'])
            ->sortBy('id');

        $propertyHeadings = $this->getHeadingsFromProperties($choiceListEntries);

        $this->choiceListEntries = $choiceListEntries->map(function (ChoiceListEntry $choiceListEntry) use ($propertyHeadings) {

            $properties = $propertyHeadings->mapWithKeys(fn(string $heading) => [$heading => $choiceListEntry->properties[$heading] ?? null]);

            return collect([
                'list_name' => $choiceListEntry->choiceList->list_name,
                'name' => $choiceListEntry->name,
                ...$this->getLanguageStrings($choiceListEntry, 'label'),
                ...$properties,
            ]);
        });

    }

    public function collection()
    {

        return $this->choiceListEntries;
    }

    public function headings(): array
    {
        return $this->choiceListEntries->first()->keys()->toArray();
    }

    public function title(): string
    {
        return 'choices';
    }

    private function getHeadingsForStringType(string $string): Collection
    {
        return $this->xlsformTemplateLanguages->map(fn(XlsformTemplateLanguage $xlsformTemplateLanguage) => "$string::{$xlsformTemplateLanguage->language->name} ({$xlsformTemplateLanguage->language->iso_alpha2})"
        );
    }

    private function getHeadingsFromProperties(Collection $choiceListEntries): Collection
    {
        return $choiceListEntries
            ->map(fn($choiceListEntry) => $choiceListEntry->properties->keys())
            ->flatten()
            ->unique();
    }

    private function getLanguageStrings(mixed $row, string $string): Collection
    {


        return $this->xlsformTemplateLanguages
            ->mapWithKeys(function (XlsformTemplateLanguage $xlsformTemplateLanguage) use ($string, $row) {

                $key = "$string::{$xlsformTemplateLanguage->language->name} ({$xlsformTemplateLanguage->language->iso_alpha2})";
                $value = $row->languageStrings
                    ->where('language_string_type_id', $this->languageStringTypes->where('name', $string)->first()->id)
                    ->where('xlsform_template_language_id', $xlsformTemplateLanguage->id)
                    ->first()?->text ?? null;
                return [$key => $value];
            });

    }

    public function styles(Worksheet $sheet)
    {
        // starting at C, make 1 column auto-wrap per Xlsformtemplatelangauge
        $wrapArray = $this->xlsformTemplateLanguages->mapWithKeys(fn(XlsformTemplateLanguage $language, $index) => [chr(67 + $index) => ['alignment' => ['wrapText' => true]]]
        )->toArray();

        return [
            1 => ['font' => ['bold' => true]],
            ...$wrapArray,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 30,
            'C' => 60,
        ];
    }
}
