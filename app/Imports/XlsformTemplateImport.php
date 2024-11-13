<?php

namespace App\Imports;

use App\Models\Language;
use App\Models\LanguageString;
use App\Models\SurveyRow;
use App\Models\XlsformTemplate;
use App\Models\LanguageStringType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\XlsformTemplateLanguage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class XlsformTemplateImport implements ToCollection, WithHeadingRow
{


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
            ->each(function ($row) use (&$currentImportTemplateLanguages, &$currentImportSurveyRows, &$stringUpdated) {

                $surveyRow = $this->xlsformTemplate->surveyRows()->updateOrCreate(['name' => $row['name']], []);
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


    // Check if the column is translatable
    // Note: column heading is sanitised by WithHeadingRow e.g., 'label::English (en)' becomes 'labelenglish_en'
    private function isTranslatableColumn(string $column): bool
    {
        $types = LanguageStringType::pluck('name')->toArray();
        $pattern = '/^(' . implode('|', $types) . ')[a-z]+_[a-z]{2}$/';
        return preg_match($pattern, $column);
    }

    // Extract language string type and language from column heading
    private function extractTypeAndLanguage(string $column): array
    {
        $types = LanguageStringType::pluck('name')->toArray();
        $languages = Language::pluck('iso_alpha2')->toArray();

        $typesPattern = implode('|', array_map('preg_quote', $types));
        $languagesPattern = implode('|', array_map('preg_quote', $languages));

        if (preg_match('/^(' . $typesPattern . ')([a-z]+)_(' . $languagesPattern . ')$/', $column, $matches)) {
            $type = $matches[1];         // The matched type
            $language = $matches[3];      // The language code (e.g., 'en', 'es')

            return [
                LanguageStringType::firstWhere('name', $type),
                Language::firstWhere('iso_alpha2', $language),
            ];

        } else {
            // Log an error when the column does not match expected format and return null values
            abort(500, "Column heading does not match expected format: {$column}");
        }
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

    // Create or update a LanguageString
    // returns a boolean to indicate if a change was made or not.
    private function createLanguageString(SurveyRow $surveyRow, XlsformTemplateLanguage $templateLanguage, LanguageStringType $type, string $value): bool
    {

        $existingLanguageString = LanguageString::where([
            'survey_row_id' => $surveyRow->id,
            'xlsform_template_language_id' => $templateLanguage->id,
            'language_string_type_id' => $type->id,
        ])->first();

        if ($existingLanguageString) {
            if ($existingLanguageString->text !== $value) {
                $existingLanguageString->update(['text' => $value]);
                return true; // Indicate a change was made
            }
        } else {
            LanguageString::create([
                'survey_row_id' => $surveyRow->id,
                'xlsform_template_language_id' => $templateLanguage->id,
                'language_string_type_id' => $type->id,
                'text' => $value,
            ]);
            return true;
        }

        // no change was made;
        return false;

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

}
