<?php

namespace App\Exports;

use App\Models\SurveyRow;
use App\Models\LanguageString;
use App\Models\LanguageStringType;
use App\Models\XlsformTemplateLanguage;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;

class XlsformTemplateLanguageExport implements FromArray, WithHeadings, WithTitle, WithColumnWidths, WithStyles, WithBackgroundColor
{
    private $xlsformTemplateId;

    public function __construct($templateId)
    {
        $this->xlsformTemplateId = $templateId;
    }

    public function headings(): array
    {
        // Get all XlsformTemplateLanguages for this template
        $templateLanguages = XlsformTemplateLanguage::where('xlsform_template_id', $this->xlsformTemplateId)
            ->get()
            ->map(function ($templateLanguage) {
                // Create a heading for the template language
                $languageName = $templateLanguage->language->name;
                $isoAlpha2 = $templateLanguage->language->iso_alpha2;
                return $templateLanguage->description 
                    ? $languageName . ' (' . $isoAlpha2 . ') - ' . $templateLanguage->description
                    : $languageName . ' (' . $isoAlpha2 . ')';
            })
            ->toArray();
    
        // Headings
        return array_merge(['name', 'translation type'], $templateLanguages, ['new translation']);
    }

    public function array(): array
    {
        $exportData = [];

        // Get all survey rows linked to the template
        $surveyRows = SurveyRow::where('xlsform_template_id', $this->xlsformTemplateId)->get();

        // Get all XlsformTemplateLanguages for this template
        $templateLanguages = XlsformTemplateLanguage::where('xlsform_template_id', $this->xlsformTemplateId)->get();

        foreach ($surveyRows as $surveyRow) {
            // Get all language strings for this survey row
            $languageStrings = LanguageString::where('survey_row_id', $surveyRow->id)->get();

            // Group the language strings by their 'type' (e.g., 'label', 'hint')
            $groupedByTypeId = $languageStrings->groupBy('language_string_type_id');

            // Process each type (label, hint, required_message, etc.)
            foreach ($groupedByTypeId as $typeId => $strings) {

                // Get the LanguageStringType name
                $languageStringType = LanguageStringType::find($typeId);
                $typeName = $languageStringType->name;

                // Create the initial row with the SurveyRow 'name' and 'type'
                $row = [$surveyRow->name, $typeName];

                // For each language in XlsformTemplateLanguage, add the corresponding text
                foreach ($templateLanguages as $templateLanguage) {
                    // Find the language string for this language (if it exists)
                    $stringForLanguage = $strings->firstWhere('xlsform_template_language_id', $templateLanguage->id);
                    
                    // Add the 'text' if found, or leave the cell empty
                    $row[] = $stringForLanguage ? $stringForLanguage->text : '';
                }

                // Add a blank column for 'new_translation'
                $row[] = '';

                // Add the row to the export data
                $exportData[] = $row;
            }
        }

        return $exportData;
    }

    public function backgroundColor()
    {
        // Adds fill to all rows/cols with no data
        return 'E7E7E7';
    
    }

    public function styles(Worksheet $sheet)
    {
        // Define header style
        $h1 = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'B2D23E'],
            ],
        ];

        // Define orange fill style
        $orangeFill = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'cc8800'],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
        ];

        // Define white fill style for the new translation column
        $whiteFill = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFFF'],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
        ];

        // Apply styles for the header row
        $lastColumnIndex = count($this->headings());
        $headingRange = 'A1:' . Coordinate::stringFromColumnIndex($lastColumnIndex) . '1';
        $sheet->getStyle($headingRange)->applyFromArray($h1);

        // Get total number of rows for styling
        $rowCount = count($this->array()) + 1; // +1 for the header row

        // Create a range for the data rows
        for ($rowIndex = 2; $rowIndex <= $rowCount; $rowIndex++) {
            // Apply orange fill to the entire row
            $dataRange = "A{$rowIndex}:" . Coordinate::stringFromColumnIndex($lastColumnIndex) . "{$rowIndex}";
            $sheet->getStyle($dataRange)->applyFromArray($orangeFill);
            
            // Apply white fill to the new translation column
            $sheet->getStyle(Coordinate::stringFromColumnIndex($lastColumnIndex) . "{$rowIndex}")->applyFromArray($whiteFill);
        }
    }

    public function columnWidths(): array
    {
        // Set specific widths for columns A and B
        $columnWidths = [
            'A' => 29,
            'B' => 20,
        ];
    
        // Set width for all other columns
        $lastColumnIndex = count($this->headings());
        for ($i = 3; $i <= $lastColumnIndex; $i++) { 
            $columnLetter = Coordinate::stringFromColumnIndex($i);
            $columnWidths[$columnLetter] = 45;
        }
    
        return $columnWidths;
    }

    public function title(): string
    {
        // Names the sheet
        return 'translations';
    }
}