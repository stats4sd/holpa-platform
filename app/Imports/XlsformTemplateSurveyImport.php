<?php

namespace App\Imports;

use App\Models\Language;
use App\Models\LanguageString;
use App\Models\SurveyRow;
use App\Models\XlsformTemplate;
use App\Models\LanguageStringType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\XlsformTemplateLanguage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class XlsformTemplateSurveyImport implements ToCollection, WithHeadingRow
{

    use HasTranslatableColumns;

    public function __construct(public XlsformTemplate $xlsformTemplate)
    {
    }

    public function collection(Collection $rows)
    {
        $existingSurveyRows = $this->xlsformTemplate->surveyRows()->get()->keyBy('name');

        // create or update survey rows and language strings
        // delete and survey rows (and linked language strings) for rows not in the updated import

        $headings = $rows->first()->keys();

        $currentImportTemplateLanguages = $this->extractUniqueTemplateLanguagesFromHeadings($headings);
        $currentImportSurveyRows = collect();
        $stringUpdated = false;

        $rows
            ->filter(fn($row) => !empty($row['name']))
            ->each(function (Collection $row) use (&$currentImportTemplateLanguages, &$currentImportSurveyRows, &$stringUpdated) {

                $props = $row
                    ->filter(fn($value, $key) => !$this->isTranslatableColumn($key))
                    ->filter(fn($value, $key) => !in_array($key, $this->getSurveyRowHeaders()));

                $data = $row
                    ->filter(fn($value, $key) => in_array($key, $this->getSurveyRowHeaders()))
                    ->filter(fn($value, $key) => $value !== null);

                $data['properties'] = $props;

                $surveyRow = $this->xlsformTemplate->surveyRows()->updateOrCreate(
                    ['name' => $row['name']], $data->toArray());
                $currentImportSurveyRows[] = $surveyRow;

                foreach ($row as $column => $value) {

                    if ($value !== null && $this->isTranslatableColumn($column)) {

                        [$type, $language] = $this->extractTypeAndLanguage($column);

                        $templateLanguage = $currentImportTemplateLanguages->firstWhere('language_id', $language->id);

                        // Track modifications and create/update LanguageString
                        if ($this->createLanguageString($surveyRow, $templateLanguage, $type, $value)) {
                            $stringUpdated = true;
                        }
                    }
                }

            });

        // Update `needs_update` for modified template languages
        if ($stringUpdated) {
            $this->updateNeedsUpdateForModifiedLanguages($currentImportTemplateLanguages);
        }

        // Handle survey row deletions
        $this->removeMissingSurveyRows($existingSurveyRows, $currentImportSurveyRows);

        // TODO: think about cases where the language String is nulled but the suvey row remains (e.g. someone removes a 'note' entry).
        // In this case, all langauge strings for that type/surveyRow combo should be removed...
    }


    // Extract unique languages from the headings and create XlsformTemplateLanguage instances
    private function extractUniqueTemplateLanguagesFromHeadings($headings): \Illuminate\Database\Eloquent\Collection
    {
        $templateLanguages = [];

        foreach ($headings as $heading) {
            if ($this->isTranslatableColumn($heading)) {
                [$type, $language] = $this->extractTypeAndLanguage($heading);

                // Create a new XlsformTemplateLanguage with language_id
                $templateLanguages[] = XlsformTemplateLanguage::firstOrCreate([
                    'language_id' => $language->id,
                    'xlsform_template_id' => $this->xlsformTemplate->id,
                ], [
                        'has_language_strings' => 1,
                    ]
                );
            }
        }

        return (new XlsformTemplateLanguage)->newCollection($templateLanguages);
    }

    // (Template edit) Remove survey rows and associated language strings if not in the current import
    private function removeMissingSurveyRows(Collection $existingSurveyRows, Collection $currentSurveyRows): void
    {
        $existingSurveyRows->each(function (SurveyRow $surveyRow) use ($currentSurveyRows) {

            if (!$currentSurveyRows->contains($surveyRow)) {
                // If row is missing, delete it and its language strings
                LanguageString::where('survey_row_id', $surveyRow->id)->delete();
                $surveyRow->delete();
            }
        });
    }

    // (Template edit) Set needs_update for template languages not in the current import
    private function updateNeedsUpdateForModifiedLanguages(\Illuminate\Database\Eloquent\Collection $templateLanguages): void
    {


        // Get template languages associated with the template that are NOT in the current import
        $missingTemplateLanguages = $this->xlsformTemplate->xlsformTemplateLanguages
            ->filter(fn(XlsformTemplateLanguage $templateLanguage) => !$templateLanguages->contains($templateLanguage->id));


        foreach ($missingTemplateLanguages as $templateLanguage) {
            $templateLanguage->update(['needs_update' => 1]);
        }
    }

    private function getSurveyRowHeaders(): array
    {
        return
            [
                'name',
                'type',
                'required',
                'relevant',
                'appearance',
                'calculation',
                'constraint',
                'choice_filter',
                'repeat_count',
                'default',
                'note',
                'trigger',
            ];
    }

}
