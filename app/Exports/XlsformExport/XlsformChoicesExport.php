<?php

namespace App\Exports\XlsformExport;

use App\Models\Language;
use App\Models\Xlsforms\ChoiceListEntry;
use App\Models\Xlsforms\Xlsform;
use App\Models\Xlsforms\XlsformTemplateLanguage;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsformChoicesExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnWidths
{
    /**
     * @var Collection<ChoiceListEntry>
     */
    public Collection $choiceListEntries;

    public function __construct(public Xlsform $xlsform, public Collection $languages, public Collection $languageStringTypes)
    {

        // get collection in construct so we can use props to get headings
        // simplest case - return all survey rows from the template
        // TODO: get choice list entries from custom indicators
        $choiceListEntries = $this->xlsform
            ->xlsformTemplate
            ->choiceListEntries
            ->load(['languageStrings', 'choiceList'])
            ->sortBy(['choice_list_id', 'id']);

        $propertyHeadings = $this->getHeadingsFromProperties($choiceListEntries);

        $this->choiceListEntries = $choiceListEntries->map(function (ChoiceListEntry $choiceListEntry) use ($propertyHeadings) {

            $properties = $propertyHeadings->mapWithKeys(fn(string $heading) => [$heading => $choiceListEntry->properties[$heading] ?? null]);

            return collect([
                'list_name' => $choiceListEntry->choiceList->list_name,
                'name' => Str::snake($choiceListEntry->name), // make sure the name has no spaces
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
            ->map(fn($choiceListEntry) => $choiceListEntry->properties?->keys())
            ->filter() // only non-nulls
            ->flatten()
            ->unique();
    }

    private function getLanguageStrings(mixed $row, string $string): Collection
    {


        return $this->languages
            ->mapWithKeys(function (Language $language) use ($string, $row) {

                $key = "$string::{$language->name} ({$language->iso_alpha2})";
                $value = $row->languageStrings()
                    ->whereHas('languageStringType', fn($query) => $query->where('name', $string))
                    ->first()?->text ?? null;
                return [$key => $value];
            });

    }

    public function styles(Worksheet $sheet)
    {
        // starting at C, make 1 column auto-wrap per Xlsformtemplatelangauge
        $wrapArray = $this->languages->mapWithKeys(fn(Language $language, $index) => [chr(67 + $index) => ['alignment' => ['wrapText' => true]]]
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
