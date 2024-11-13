<?php

namespace App\Imports;

use App\Models\HasLanguageStrings;
use App\Models\Language;
use App\Models\LanguageString;
use App\Models\LanguageStringType;
use App\Models\XlsformTemplateLanguage;

trait HasTranslatableColumns
{
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

        // Create or update a LanguageString
    // returns a boolean to indicate if a change was made or not.
    private function createLanguageString(HasLanguageStrings $entry, XlsformTemplateLanguage $templateLanguage, LanguageStringType $type, string $value): bool
    {

        $existingLanguageString = $entry->languageStrings()->where([
            'xlsform_template_language_id' => $templateLanguage->id,
            'language_string_type_id' => $type->id,
        ])->first();

        if ($existingLanguageString) {
            if ($existingLanguageString->text !== $value) {
                $existingLanguageString->update(['text' => $value]);
                return true; // Indicate a change was made
            }
        } else {
            $entry->languageStrings()->create([
                'xlsform_template_language_id' => $templateLanguage->id,
                'language_string_type_id' => $type->id,
                'text' => $value,
            ]);
            return true;
        }

        // no change was made;
        return false;

    }
}
