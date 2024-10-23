<?php

namespace App\Imports;

use App\Models\Language;
use App\Models\SurveyRow;
use App\Models\LanguageString;
use App\Models\LanguageStringType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\XlsformTemplateLanguage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class XlsformTemplateImport implements ToCollection, WithHeadingRow
{
    private $uniqueTemplateLanguages = [];
    private $xlsformTemplateId;

    public function __construct($templateId)
    {
        $this->xlsformTemplateId = $templateId;
    }

    public function collection(Collection $rows)
    {
        // Get the heading row to extract template languages
        $headings = $rows->first()->keys();
        $this->extractUniqueTemplateLanguagesFromHeadings($headings);

        // Loop over the survey rows
        $rows->each(function ($row) {
            if (isset($row['name']) && !empty($row['name'])) {

                // Create a new SurveyRow
                $surveyRow = SurveyRow::create([
                    'name' => $row['name'],
                    'xlsform_template_id' => $this->xlsformTemplateId,
                ]);

                // Loop over the columns
                foreach ($row as $column => $value) {
                    if ($value !== null) {

                        // Check if the column is translatable
                        if ($this->isTranslatableColumn($column)) {
                            [$type, $language] = $this->extractTypeAndLanguage($column);

                            $xlsformTemplateLanguageId = $this->uniqueTemplateLanguages[$language];

                            // Create a new LanguageString
                            $this->createLanguageString($surveyRow->id, $xlsformTemplateLanguageId, $type, $value);
                        }
                    }
                }
            }
        });
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
                        'xlsform_template_id' => $this->xlsformTemplateId,
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
}