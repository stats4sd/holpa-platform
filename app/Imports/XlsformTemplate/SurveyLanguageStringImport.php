<?php

namespace App\Imports\XlsformTemplate;

use App\Models\LanguageString;
use App\Models\LanguageStringType;
use App\Models\SurveyRow;
use App\Models\XlsformTemplate;
use App\Models\XlsformTemplateLanguage;
use App\Services\XlsformTranslationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Row;

// Import the language string - 1 per survey row; for the specific heading given in the constructor.
class SurveyLanguageStringImport implements ToModel, WithHeadingRow, ShouldQueue, WithChunkReading, SkipsEmptyRows, WithEvents, WithUpserts
{

    use Importable;
    use RegistersEventListeners;

    public XlsformTranslationHelper $xlsformTranslationHelper;
    public XlsformTemplateLanguage $xlsformTemplateLanguage;
    public LanguageStringType $languageStringType;

    public function __construct(public XlsformTemplate $xlsformTemplate, public string $heading)
    {
        $this->xlsformTranslationHelper = new XlsformTranslationHelper();

        $language = $this->xlsformTranslationHelper->getLanguageFromColumnHeader($this->heading);
        $this->languageStringType = $this->xlsformTranslationHelper->getLanguageStringTypeFromColumnHeader($this->heading);

        $this->xlsformTemplateLanguage = $this->xlsformTemplate->xlsformTemplateLanguages()
            ->where('language_id', $language->id)
            ->whereHas('locale', fn(Builder $query) => $query->where('description', null))
            ->first();
    }


    public function model(array $row): ?LanguageString
    {
        $row = collect($row);

        $surveyRow = $this->xlsformTemplate->surveyRows
            ->filter(fn(SurveyRow $surveyRow) => $surveyRow->name === $row['name'])
            ->first();

        $translatableValue = $row
            ->filter(fn($value, $key) => $this->heading === $key)
            ->filter(fn($value, $key) => $value !== null && $value !== '')
            ->first();

        if (!$translatableValue) {
            return null;
        }


        return new LanguageString([
            'linked_entry_id' => $surveyRow->id,
            'linked_entry_type' => SurveyRow::class,
            'xlsform_template_language_id' => $this->xlsformTemplateLanguage->id,
            'language_string_type_id' => $this->languageStringType->id,
            'text' => $row[$this->heading],
            'updated_during_import' => true,
        ]);

    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function isEmptyWhen(array $row): bool
    {
        return !isset($row['name']) | $row['name'] === '';  // no need to import rows without a name, as we will never have translation strings for end_group or end_repeat rows.
    }

    public function afterSheet(AfterSheet $event): void
    {
        // TODO: THis is not running properly. Fix!!

        ray('after importing languageStrings for xlsform template : ' . $this->xlsformTemplate?->id);

        $languageStringTypeId = $this->xlsformTranslationHelper->getLanguageStringTypeFromColumnHeader($this->heading)->id;
        $templateLanguageId = $this->xlsformTranslationHelper->getDefaultLanguageTemplateFromColumnHeaderAndTemplate($this->xlsformTemplate, $this->heading)->id;

        // find all Survey Rows linked to the XlsformTemplate that were not updated during the import... and delete them.
        $this->xlsformTemplate
            ->languageStrings()
            ->where('language_string_type_id', $languageStringTypeId)
            ->where('xlsform_template_language_id', $templateLanguageId)
            ->where('updated_during_import', false)
            ->delete();


        // reset updated_during_import to false for all language strings - ready for the next import
        $this->xlsformTemplate
            ->languageStrings()
            ->where('language_string_type_id', $languageStringTypeId)
            ->where('xlsform_template_language_id', $templateLanguageId)
            ->update(['updated_during_import' => false]);


    }

    public function uniqueBy(): array
    {
        return ['xlsform_template_language_id', 'linked_entry_id', 'linked_entry_type', 'language_string_type_id'];
    }
}
