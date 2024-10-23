<?php

namespace App\Imports;

use App\Models\SurveyRow;
use App\Models\LanguageString;
use App\Models\LanguageStringType;
use App\Models\XlsformTemplateLanguage;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class XlsformTemplateLanguageImport implements OnEachRow, WithHeadingRow
{
    protected $templateLanguage;

    public function __construct(XlsformTemplateLanguage $xlsformTemplateLanguage)
    {
        $this->xlsformTemplateLanguage = $xlsformTemplateLanguage;
    }

    public function onRow(Row $row)
    {
        $rowData = $row->toArray();

        $surveyRowName = $rowData['name'];
        $newTranslation = $rowData['new_translation'];
        $translationType = $rowData['translation_type'];

        // Find the template and survey row
        $template = $this->xlsformTemplateLanguage->xlsformTemplate;
        $surveyRow = $template->surveyRows()->where('name', $surveyRowName)->first();

        if ($surveyRow) {
            // Find the language string type
            $languageStringType = LanguageStringType::where('name', $translationType)->first();

            if ($languageStringType) {
                // Create a new language string
                $surveyRow->languageStrings()->create([
                    'xlsform_template_language_id' => $this->xlsformTemplateLanguage->id,
                    'language_string_type_id' => $languageStringType->id,
                    'text' => $newTranslation,
                ]);
            }
        }

        // Update the template language
        $this->xlsformTemplateLanguage->update(['has_language_strings' => 1]);
    }
}
