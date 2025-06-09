<?php

namespace App\Imports;

use App\Filament\App\Clusters\Localisations\Resources\ChoiceListEntryResource\Widgets\ChoiceListEntriesInfo;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Stats4sd\FilamentOdkLink\Models\OdkLink\ChoiceListEntry;
use Stats4sd\FilamentOdkLink\Models\OdkLink\LanguageString;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\LanguageStringType;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;

class XlsformTemplateLanguageImport implements ToModel, WithUpserts, SkipsEmptyRows, WithStrictNullComparison, ShouldQueue, WithChunkReading, WithCalculatedFormulas
{
    protected Collection $languageStringTypes;

    public function __construct(public Locale $locale)
    {
        // get these once to avoid n+1 database queries
        $this->languageStringTypes = LanguageStringType::all();
    }

//    public function headingRow(): int
//    {
//        return 1;
//    }

    /**
     * @throws Exception
     */
    public function model(array $row): LanguageString
    {

        // Fetch the translation from the row data
        $translation = $row[6];

        // Find the corresponding language string type
        $languageStringType = $this->languageStringTypes->where('name', $row[4])->first();

        $entityModel = match ($row[0]) {
            'survey' => SurveyRow::class,
            'choices' => ChoiceListEntry::class,
            default => null,
        };

        $tableName = match ($row[0]) {
            'survey' => 'survey_rows',
            'choices' => 'choice_list_entries',
            default => null,
        };

        // these should already be checked during the validation step.
        if (!$entityModel || !$tableName || !$languageStringType) {
            throw new Exception("Invalid row type: {$row[0]}");
        }

        return new LanguageString([
            'locale_id' => $this->locale->id,
            'language_string_type_id' => $languageStringType->id,
            'linked_entry_id' => $row[2],
            'linked_entry_type' => $entityModel,
            'text' => $row[6],
           // 'updated_during_import' => 1, - TODO: do we need this?
            ]);

    }

    public function uniqueBy(): array
    {
       return [
           'locale_id',
           'language_string_type_id',
           'linked_entry_id',
           'linked_entry_type',
       ];
    }

    public function isEmptyWhen(array $row): bool
    {
        return $row[2] === '' || $row[3] === '' || $row[0] === 'row type';
    }

    public function chunkSize(): int
    {
        return 200;
    }
}
