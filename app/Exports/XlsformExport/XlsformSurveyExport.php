<?php

namespace App\Exports\XlsformExport;

use App\Models\Locale;
use App\Models\Team;
use App\Models\Xlsforms\Xlsform;
use App\Models\XlsformTemplateLanguage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Row;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;

class XlsformSurveyExport implements FromCollection, WithHeadings, WithTitle, WithMapping
{

    public function __construct(public Xlsform $xlsform, public Collection $xlsformTemplateLanguages, public Collection $languageStringTypes)
    {
        ray($xlsform->xlsformTemplate->id);
        ray($xlsform->xlsformTemplate->xlsformTemplateLanguages);
        ray($this->xlsformTemplateLanguages);

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        // simplest case - return all survey rows from the template
        ray($this->xlsform->xlsformTemplate->surveyRows->sortBy('id')->load('languageStrings')
        ->pluck('languageStrings'));
        return $this->xlsform
            ->xlsformTemplate
            ->surveyRows
            ->sortBy('id')
            ->load('languageStrings');
    }

    public function map($row): array
    {
        return [
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
    }

    public function headings(): array
    {
        return [
            'type',
            'name',
            ...$this->getHeadingsForStringType('label'),
            ...$this->getHeadingsForStringType('hint'),
            'required',
            ...$this->getHeadingsForStringType('required_message'),
            'calculation',
            'relevant',
            ...$this->getHeadingsForStringType('relevant_message'),
            'appearance',
            'constraint',
            ...$this->getHeadingsForStringType('constraint_message'),
            'choice_filter',
            'repeat_count',
            'default',
        ];
    }

    public function title(): string
    {
        return 'survey';
    }

    private function getHeadingsForStringType(string $string): Collection
    {

        return $this->xlsformTemplateLanguages->map(fn(XlsformTemplateLanguage $xlsformTemplateLanguage) => "$string::{$xlsformTemplateLanguage->language->name} ({$xlsformTemplateLanguage->language->iso_alpha2})"
        );
    }

    private function getLanguageStrings(mixed $row, string $string): Collection
    {

        return $this->xlsformTemplateLanguages
            ->map(fn(XlsformTemplateLanguage $xlsformTemplateLanguage) => $row
                ->languageStrings
                ->where('language_string_type_id', $this->languageStringTypes->where('name', $string)->first()->id)
                ->where('xlsform_template_language_id', $xlsformTemplateLanguage->id)
                ->first()?->text ?? ''
            );

    }


}
