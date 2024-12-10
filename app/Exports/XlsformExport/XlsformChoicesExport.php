<?php

namespace App\Exports\XlsformExport;

use App\Models\Xlsforms\Xlsform;
use App\Models\XlsformTemplateLanguage;
use App\Models\XlsformTemplates\ChoiceListEntry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class XlsformChoicesExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    /**
     * @var Collection<ChoiceListEntry>
     */
    public Collection $choiceListEntries;

     public function __construct(public Xlsform $xlsform, public Collection $xlsformTemplateLanguages, public Collection $languageStringTypes)
    {

        // get collection in construct so we can use props to get headings
        // simplest case - return all survey rows from the template
        $this->choiceListEntries = $this->xlsform
            ->xlsformTemplate
            ->choiceListEntries
            ->load(['languageStrings', 'choiceList']);

    }

    public function collection()
    {

        return $this->choiceListEntries;
    }

    public function map($row): array
    {
        return [
            'list_name' => $row->choiceList->name,
            'name' => $row->name,
            ...$this->getLanguageStrings($row, 'label'),
            ...$row->properties,
        ];
    }

    public function headings(): array
    {
        $headings = collect();

        $headings[] = 'list_name';
        $headings[] = 'name';

        $headings->merge($this->getHeadingsForStringType('label'));
        $headings->merge($this->getHeadingsFromProperties());

        return $headings->toArray();
    }

    public function title(): string
    {
        return 'choices';
    }

    private function getHeadingsForStringType(string $string): Collection
    {
        return $this->xlsformTemplateLanguages->map(fn(XlsformTemplateLanguage $xlsformTemplateLanguage) =>
            "$string::{$xlsformTemplateLanguage->language->name} ({$xlsformTemplateLanguage->language->iso_alpha2})"
        );
    }

    private function getHeadingsFromProperties(): Collection
    {
        return $this->choiceListEntries
            ->map(fn($choiceListEntry) => $choiceListEntry->properties->keys())
            ->flatten()
            ->unique();
    }

    private function getLanguageStrings(mixed $row, string $string)
    {
        return $this->xlsformTemplateLanguages
            ->map(fn(XlsformTemplateLanguage $xlsformTemplateLanguage) => $row
                ->languageStrings
                ->where('language_string_type_id', $this->languageStringTypes->where('name', $string)->first()->id)
                ->where('language_id', $xlsformTemplateLanguage->language_id)
                ->first()?->text ?? null
            );
    }
}
