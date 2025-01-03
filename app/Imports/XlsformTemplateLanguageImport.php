<?php

namespace App\Imports;

use App\Models\XlsformLanguages\LanguageStringType;
use App\Models\XlsformLanguages\XlsformTemplateLanguage;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class XlsformTemplateLanguageImport implements OnEachRow, WithHeadingRow
{
    protected $headerMap = [];

    public function __construct(XlsformTemplateLanguage $xlsformTemplateLanguage)
    {
        $this->xlsformTemplateLanguage = $xlsformTemplateLanguage;
    }

    // Normalize header names for comparison
    protected function normalizeHeading($heading)
    {
        $normalized = strtolower(trim($heading));
        $normalized = preg_replace('/[ -]+/', '_', $normalized);

    }

    // Preprocess headers to create a map of normalized column names to actual column names
    public function prepareForImport(array $headings)
    {
        foreach ($headings as $heading) {
            $normalizedHeading = $this->normalizeHeading($heading);
            $this->headerMap[$normalizedHeading] = $heading;
        }
    }

    // Specify the heading row
    public function headingRow(): int
    {
        return 1;
    }

    public function onRow(Row $row)
    {
        $rowData = $row->toArray();

        // Check if this is the first row
        if (empty($this->headerMap)) {
            $this->prepareForImport(array_keys($rowData));
        }

        $surveyRowName = $rowData['name'] ?? null;
        $translationType = $rowData['translation_type'] ?? null;

        if (!$surveyRowName || !$translationType) {
            return; // Skip row if data is missing
        }

        // Get the actual column for the current language using the header map
        $normalizedLanguageLabel = $this->normalizeHeading($this->xlsformTemplateLanguage->localeLanguageLabel);
        $actualLanguageColumn = $this->headerMap[$normalizedLanguageLabel] ?? null;

        // Fetch the translation from the row data
        $translation = $actualLanguageColumn ? $rowData[$actualLanguageColumn] ?? null : null;

        // Check if the survey row exists in the template
        $template = $this->xlsformTemplateLanguage->xlsformTemplate;
        $surveyRow = $template->surveyRows()->where('name', $surveyRowName)->first();

        if ($surveyRow) {
            // Find the corresponding language string type
            $languageStringType = LanguageStringType::where('name', $translationType)->first();

            if ($languageStringType) {
                if ($translation === null) {
                    return;
                }

                // Create or update the language string for this survey row and template language
                $surveyRow->languageStrings()->updateOrCreate(
                    [
                        'xlsform_template_language_id' => $this->xlsformTemplateLanguage->id,
                        'language_string_type_id' => $languageStringType->id,
                    ],
                    [
                        'text' => $translation,
                    ]
                );
            }
        }

        // Update the template language to mark that it has language strings
        $this->xlsformTemplateLanguage->update(['has_language_strings' => 1]);

        // Unset needs_update if it was previously set
        if ($this->xlsformTemplateLanguage->needs_update) {
            $this->xlsformTemplateLanguage->update(['needs_update' => 0]);
        }
    }
}
