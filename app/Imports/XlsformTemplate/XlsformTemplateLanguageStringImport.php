<?php

namespace App\Imports\XlsformTemplate;

use App\Jobs\FinishLanguageStringImport;
use App\Models\ChoiceList;
use App\Models\ChoiceListEntry;
use App\Models\Language;
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
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Events\AfterImport;
use function Symfony\Component\String\s;

class XlsformTemplateLanguageStringImport implements WithMultipleSheets, ShouldQueue, WithChunkReading, WithEvents, ToModel, WithHeadingRow, WithUpserts, SkipsEmptyRows
{
    use RegistersEventListeners;
    use Importable;

    public XlsformTranslationHelper $xlsformTranslationHelper;
    public XlsformTemplateLanguage $xlsformTemplateLanguage;
    public LanguageStringType $languageStringType;
    public ?string $relationship;

    public function __construct(public XlsformTemplate $xlsformTemplate, public string $heading, public string $sheet)
    {

        // init translation helper and get needed props from the provided heading;
        $this->xlsformTranslationHelper = new XlsformTranslationHelper();
        $language = $this->xlsformTranslationHelper->getLanguageFromColumnHeader($this->heading);
        $this->languageStringType = $this->xlsformTranslationHelper->getLanguageStringTypeFromColumnHeader($this->heading);

        $this->xlsformTemplateLanguage = $this->xlsformTemplate->xlsformTemplateLanguages()
            ->where('language_id', $language->id)
            ->whereHas('locale', fn(Builder $query) => $query->where('description', null))
            ->first();

        $this->class = match ($sheet) {
            'survey' => 'surveyRows',
            'choices' => 'choiceListEntries',
            default => null
        };

    }

    // Specify the "survey" sheet
    public function sheets(): array
    {
        if (!$this->class) {
            return []; // no actions if the class / worksheet is not recognised.
        }

        return [
            $this->sheet => $this,
        ];
    }


    public function isEmptyWhen(array $row): bool
    {
        return !isset($row['name']) | $row['name'] === '';  // no need to import rows without a name, as we will never have translation strings for end_group or end_repeat rows.
    }

    public function uniqueBy(): array
    {
        return ['xlsform_template_language_id', 'linked_entry_id', 'linked_entry_type', 'language_string_type_id'];
    }


    public function model(array $row): ?LanguageString
    {
        $row = collect($row);

        $class = $this->class;

        $items = $this->xlsformTemplate->$class
            ->filter(fn($item) => (string)$item->name === (string)$row['name']);

        if($row['name'] === 'id' && $row['list_name'] === 'enumerator') {
            ray($items);
        }

        // filter choice list entries by choice_list as well as name
        if($class === 'choiceListEntries') {
            $items = $items
                ->filter(fn($item) => (string)$item->choiceList->list_name === (string)$row['list_name']);
        }

        if($row['name'] === 'id' && $row['list_name'] === 'enumerator') {
            ray($items);
        }

        $item = $items->first();


        if (!$item) {
            ray('No item found for row', $row, $class);
        }

        $translatableValue = $row
            ->filter(fn($value, $key) => $this->heading === $key)
            ->filter(fn($value, $key) => $value !== null && $value !== '')
            ->first();

        if (!$translatableValue) {
            return null;
        }

        return new LanguageString([
            'linked_entry_id' => $item->id,
            'linked_entry_type' => $this->class,
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

    public function afterImport(AfterImport $event): void
    {
        $languageStringTypeId = $this->xlsformTranslationHelper->getLanguageStringTypeFromColumnHeader($this->heading)->id;
        $templateLanguageId = $this->xlsformTranslationHelper->getDefaultLanguageTemplateFromColumnHeaderAndTemplate($this->xlsformTemplate, $this->heading)->id;
        $type = match ($this->sheet) {
            'survey' => SurveyRow::class,
            'choices' => ChoiceListEntry::class,
            default => null
        };

        // find all Survey Rows linked to the XlsformTemplate that were not updated during the import... and delete them.
        $toDelete = $this->xlsformTemplate
            ->languageStrings()
            ->where('language_string_type_id', $languageStringTypeId)
            ->where('xlsform_template_language_id', $templateLanguageId)
            ->where('linked_entry_type', $type)
            ->where('updated_during_import', false)
            ->get();

        $toDelete->each(fn(LanguageString $languageString) => $languageString->delete());


    }

}
