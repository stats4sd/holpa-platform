<?php

namespace App\Imports;

use App\Models\ChoiceList;
use App\Models\LanguageStringType;
use App\Models\XlsformTemplate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class XlsformTemplateChoicesImport implements ToCollection, WithHeadingRow
{

    use HasTranslatableColumns;

    public function __construct(public XlsformTemplate $xlsformTemplate)
    {
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $headings = $rows->first()->keys();

        $languageHeadings = $headings->filter(fn(string $heading): bool => $this->isTranslatableColumn($heading));

        $importedChoices = $rows
            ->filter(fn(Collection $row): bool => !empty($row['list_name']))
            ->map(function (Collection $row) use ($languageHeadings, &$currentImportChoiceLists, &$currentImportChoices) {

                $choiceList = $this->xlsformTemplate->choiceLists()->updateOrCreate(['list_name' => $row['list_name']], []);

                // props are all columns that are not translatable + not list_name or name;
                $props = $row
                    ->filter(fn($value, $key) => !$this->isTranslatableColumn($key))
                    ->filter(fn($value, $key) => !in_array($key, ['list_name', 'name']));

                $choice = $choiceList->choiceListEntries()->updateOrCreate(['name' => $row['name'],], [
                    'properties' => $props,
                ]);

                $translatableColumns = $row->only($languageHeadings->toArray());

                $translatableColumns->each(function ($value, $column) use ($choice) {

                    [$type, $language] = $this->extractTypeAndLanguage($column);

                    $xlsformTemplateLanguage = $this->xlsformTemplate->xlsformTemplateLanguages()->whereHas('language', fn($query) => $query->where('id', $language->id))->first();

                    $this->createLanguageString($choice, $xlsformTemplateLanguage, $type, $value);
                });

                return $choice;
            });

        ray($importedChoices);
        $importedChoiceLists = $importedChoices->pluck('choiceList')->unique();
        ray($importedChoiceLists);

        // delete any choices that were not in the import
        $this->xlsformTemplate->choiceLists->each(function (ChoiceList $choiceList) use ($importedChoices) {
            $choiceList->choiceListEntries()->each(function ($choice) use ($importedChoices) {
                if (!$importedChoices->contains('id', $choice->id)) {
                    $choice->delete();
                }
            });
        });

        // delete any choice lists that were not in the import
        $this->xlsformTemplate->choiceLists->each(function (ChoiceList $choiceList) {
            if($choiceList->choiceListEntries()->count() === 0) {
                $choiceList->delete();
            }
        });

    }

}
