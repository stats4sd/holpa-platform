<?php

namespace App\Imports\XlsformTemplate;

use App\Models\SurveyRow;
use App\Models\XlsformTemplate;
use App\Services\XlsformTranslationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Row;

class SurveyLanguageStringImport implements OnEachRow, WithHeadingRow, ShouldQueue, WithChunkReading, SkipsEmptyRows
{

    use Importable;

    public XlsformTranslationHelper $xlsformTranslationHelper;

    public function __construct(public XlsformTemplate $xlsformTemplate, public Collection $translatableHeadings)
    {
        $this->xlsformTranslationHelper = new XlsformTranslationHelper();
    }


    public function onRow(Row $row): void
    {
        $row = $row->toCollection();

        $this->xlsformTemplate
            ->refresh() // to make sure we have all the imported survey Rows
            ->load('surveyRows');


        $surveyRow = SurveyRow::all()
            // $surveyRow = $this->xlsformTemplate->surveyRows
            ->filter(fn(SurveyRow $surveyRow) => $surveyRow->name === $row['name'])
            ->first();

        $translatableValues = $row
            ->filter(fn($value, $key) => $this->translatableHeadings->contains($key))
            ->filter(fn($value, $key) => $value !== null && $value !== '')
            ->map(function ($value, $key) {

                $language = $this->xlsformTranslationHelper->getLanguageFromColumnHeader($key);
                $languageStringType = $this->xlsformTranslationHelper->getLanguageStringTypeFromColumnHeader($key);

                $xlsformTemplateLanguage = $this->xlsformTemplate->xlsformTemplateLanguages()
                    ->where('language_id', $language->id)
                    ->whereHas('locale', fn(Builder $query) => $query->where('description', null))
                    // we assume the default language is always English
                    ->first();

                return [
                    'xlsform_template_language_id' => $xlsformTemplateLanguage->id,
                    'language_string_type_id' => $languageStringType->id,
                    'text' => $value,
                ];
            });

        $surveyRow->languageStrings()->createMany($translatableValues);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function isEmptyWhen(array $row): bool
    {
        return !isset($row['name'])| $row['name'] === '';  // no need to import rows without a name, as we will never have translation strings for end_group or end_repeat rows.
    }
}
