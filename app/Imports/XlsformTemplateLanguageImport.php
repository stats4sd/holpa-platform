<?php

namespace App\Imports;

use App\Models\LanguageString;
use App\Models\SurveyRow;
use App\Models\LanguageStringType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class XlsformTemplateLanguageImport implements ToModel, WithHeadingRow
{
    protected $templateId;
    protected $templateLanguageId;

    public function __construct($templateId, $templateLanguageId)
    {
        $this->templateId = $templateId;
        $this->templateLanguageId = $templateLanguageId;
    }

    public function model(array $row)
    {
        $surveyRowName = $row['name'];
        $newTranslation = $row['new_translation'];
        $translationType = $row['translation_type'];

        // Find the survey row
        $surveyRow = SurveyRow::where('xlsform_template_id', $this->templateId)
            ->where('name', $surveyRowName)
            ->first();

        if ($surveyRow) {
            // Find the language string type
            $languageStringType = LanguageStringType::where('name', $translationType)->first();

            if ($languageStringType) {
                // Create a new language string
                LanguageString::create([
                    'xlsform_template_language_id' => $this->templateLanguageId,
                    'survey_row_id' => $surveyRow->id,
                    'language_string_type_id' => $languageStringType->id,
                    'text' => $newTranslation,
                ]);
            }
        }
    }
}
