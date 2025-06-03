<?php

namespace App\Imports;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Stats4sd\FilamentOdkLink\Models\OdkLink\ChoiceListEntry;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\LanguageStringType;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;

class XlsformTemplateLanguageImport implements OnEachRow, SkipsEmptyRows, WithHeadingRow, WithStrictNullComparison, WithValidation, ShouldQueue, WithChunkReading, WithCalculatedFormulas
{
    protected array $headerMap = [];

    public function __construct(public XlsformTemplate $xlsformTemplate, public Locale $locale) {}

    public function headingRow(): int
    {
        return 1;
    }

    /**
     * @throws Exception
     */
    public function onRow(Row $row): void
    {
        $rowData = $row->toArray();


       // dump($rowData);

        // Get the actual column for the current language using the header map
        $normalizedLanguageLabel = Str::slug($this->locale->language_label, '_');

        // Fetch the translation from the row data
        $translation = $rowData[$normalizedLanguageLabel];

        // Find the corresponding language string type
        $languageStringType = LanguageStringType::where('language_string_types.name', $rowData['translation_type'])->first();

        $relationship = match ($rowData['row_type']) {
            'survey' => 'surveyRows',
            'choices' => 'choiceListEntries',
            default => null,
        };

        $tableName = match ($rowData['row_type']) {
            'survey' => 'survey_rows',
            'choices' => 'choice_list_entries',
            default => null,
        };

        // these should already be checked during the validation step.
        if (! $relationship || ! $tableName || ! $languageStringType) {
            throw new Exception("Invalid row type: {$rowData['row_type']}");
        }

        /** @var SurveyRow|ChoiceListEntry $entries */
        $entries = $this->xlsformTemplate->$relationship()->where("$tableName.name", $rowData['name'])->get();

        // survey rows within a specific template will be unique by name, except for begin and end group / repeats. Choice List Entries will not be, so we also check the choice_list_id
        if ($rowData['row_type'] === 'choices') {
            $entries = $entries->filter(fn (ChoiceListEntry $entry) => $entry->choiceList->id === (int) $rowData['choice_list_id']);
        }

        // Normally, there will only be one entry here. However:
        // - choice lists may have multiple entries with the same name, e.g. when using choice filters.
        // - survey rows may have multiple entries with the same name in the specific case of group + repeats.
        // In both cases, the items with the same name *should* have the same label/hint etc, so we can update *all* entries at once.
        foreach ($entries as $entry) {
            $entry->languageStrings()->updateOrCreate(
                [
                    'locale_id' => $this->locale->id,
                    'language_string_type_id' => $languageStringType->id,
                    'linked_entry_id' => $entry->id,
                    'linked_entry_type' => get_class($entry),
                ],
                [
                    'text' => $translation,
                    'updated_during_import' => 1,
                ]
            );
        }

        // Update the template language to mark that it has language strings

        $entries->first()
            ->xlsformModuleVersion
            ->locales()
            ->sync([$this->locale->id => ['has_language_strings' => 1, 'needs_update' => 0]], detaching: false);
    }

    public function isEmptyWhen(array $row): bool
    {
        return $row['name'] === '' || $row['translation_type'] === '';
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'translation_type' => 'required',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
