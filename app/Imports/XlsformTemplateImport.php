<?php

namespace App\Imports;

use App\Models\Language;
use App\Models\LanguageString;
use App\Models\XlsformTemplate;
use App\Models\LanguageStringType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\XlsformTemplateLanguage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class XlsformTemplateImport implements ToCollection, WithHeadingRow, WithMultipleSheets
{
    private $uniqueTemplateLanguages = [];

    public function __construct(XlsformTemplate $template)
    {
        $this->xlsformTemplate = $template;
    }

    // Specify the "survey" sheet
    public function sheets(): array
    {
        return [
            'survey' => $this,
        ];
    }

    public function collection(Collection $rows)
    {
        $isNewTemplate = !$this->xlsformTemplate->surveyRows()->exists();
        $existingSurveyRows = $this->xlsformTemplate->surveyRows()->get()->keyBy('name');
        $currentImportSurveRows = [];
        $importTemplateLanguages = [];
        
        $headings = $rows->first()->keys();
        $this->extractUniqueTemplateLanguagesFromHeadings($headings);

        $rows->each(function ($row) use (&$currentImportSurveRows, &$importTemplateLanguages, $isNewTemplate) {
            if (isset($row['name']) && !empty($row['name'])) {
                $currentImportSurveRows[] = $row['name'];

                $surveyRow = $this->xlsformTemplate->surveyRows()->updateOrCreate(['name' => $row['name']],[]);

                foreach ($row as $column => $value) {
                    if ($value !== null && $this->isTranslatableColumn($column)) {
                        [$type, $language] = $this->extractTypeAndLanguage($column);
                        $xlsformTemplateLanguageId = $this->uniqueTemplateLanguages[$language] ?? null;

                        // Track this language as being present in the new file
                        $importTemplateLanguages[$language] = true;

                        // Create or update the LanguageString
                        $this->createLanguageString($surveyRow->id, $xlsformTemplateLanguageId, $type, $value);
                    }
                }
            }
        });

        // Get all template languages and find those that are missing
        $allTemplateLanguages = $this->xlsformTemplate->xlsformTemplateLanguages()->get()->keyBy('language_id');
        $missingTemplateLanguages = $allTemplateLanguages->reject(function ($templateLanguage) use ($importTemplateLanguages) {
            return isset($importTemplateLanguages[$templateLanguage->language->iso_alpha2]);
        });

        // Set needs_update for languages missing in the file
        $this->setNeedsUpdateFormissingTemplateLanguages($missingTemplateLanguages);

        // Handle survey row deletions
        $this->removeMissingSurveyRows($existingSurveyRows, $currentImportSurveRows);
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
        $typesPattern = implode('|', array_map('preg_quote', $types));

        if (preg_match('/^(' . $typesPattern . ')([a-z]+)_([a-z]{2})$/', $column, $matches)) {
            $type = $matches[1];         // The matched type
            $language = $matches[3];      // The language code (e.g., 'en', 'es')
            return [$type, $language];
        } else {
            // Log an error when the column does not match expected format and return null values
            Log::error("Column '{$column}' does not match expected format for type and language extraction.");
            return [null, null];
        }
    }

    // Extract unique languages from the headings and create XlsformTemplateLanguage instances
    private function extractUniqueTemplateLanguagesFromHeadings($headings)
    {
        foreach ($headings as $heading) {
            if ($this->isTranslatableColumn($heading)) {
                [$type, $language] = $this->extractTypeAndLanguage($heading);

                // Get language_id from the Language model based on iso_alpha2 column
                $languageModel = Language::where('iso_alpha2', $language)->first();

                if ($languageModel) {
                    // Create a new XlsformTemplateLanguage with language_id
                    $xlsformTemplateLanguage = XlsformTemplateLanguage::firstOrCreate([
                        'language_id' => $languageModel->id,
                        'xlsform_template_id' => $this->xlsformTemplate->id,
                    ],[
                        'has_language_strings' => 1,
                    ]
                );

                    // Store the created language id for later use
                    $this->uniqueTemplateLanguages[$language] = $xlsformTemplateLanguage->id;
                } else {
                    Log::error("No language model found for code: {$language}");
                }
            }
        }
    }

    // Create a new LanguageString
    private function createLanguageString(int $surveyRowId, int $xlsformTemplateLanguageId, string $type, string $value)
    {
        $languageStringType = LanguageStringType::where('name', $type)->first();

        if ($languageStringType) {
            LanguageString::create([
                'survey_row_id' => $surveyRowId,
                'xlsform_template_language_id' => $xlsformTemplateLanguageId,
                'language_string_type_id' => $languageStringType->id,
                'text' => $value,
            ]);
        } else {
            // Log an error when the type is not found
            Log::error("Language string type not found for type: {$type}");
        }
    }

    // (Template edit) Remove survey rows and associated language strings if not in the current import
    private function removeMissingSurveyRows($existingSurveyRows, $currentImportSurveRows)
    {
        $existingSurveyRows->each(function ($surveyRow) use ($currentImportSurveRows) {
            if (!in_array($surveyRow->name, $currentImportSurveRows)) {
                // If row is missing, delete it and its language strings
                LanguageString::where('survey_row_id', $surveyRow->id)->delete();
                $surveyRow->delete();
            }
        });
    }

    // (Template edit) Set needs_update for template languages not in the current import
    private function setNeedsUpdateFormissingTemplateLanguages($missingTemplateLanguages)
    {
        foreach ($missingTemplateLanguages as $templateLanguage) {
            $templateLanguage->update(['needs_update' => 1]);
        }
    }
}
