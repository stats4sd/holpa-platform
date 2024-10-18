<?php

namespace App\Imports;

use App\Models\ChoiceList;
use App\Models\ChoiceListEntry;
use App\Models\Language;
use App\Models\LanguageStringType;
use App\Models\XlsformTemplate;
use App\Models\XlsformTemplateLanguage;
use Database\Seeders\LanguageStringTypesSeeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class XlsformTemplateChoiceListImporter implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithCalculatedFormulas
{

    public function __construct(public XlsformTemplate $xlsformTemplate)
    {
    }

    public function collection(Collection $collection)
    {

        // TEMPORARY: TODO: integrate with proper approach of creating XlsformTemplateLanguage


        $keys = $collection->first()->keys();

        // get the label columns
        $labelColumns = $keys
            ->filter(fn($key) => Str::contains($key, '::'))
            ->mapWithKeys(function ($key) {

                $languageIso = Str::between($key, '(', ')');

                $templateLanguage = $this->xlsformTemplate->xlsformTemplateLanguages()->create([
                    'language_id' => Language::where('iso_alpha2', $languageIso)->first()->id,
                ]);

                return [$templateLanguage->language_id => $key];
            });

        $collection->groupBy('list_name')
            ->each(function (Collection $itemList, string $listName) use ($labelColumns) {


                // Create the choice list
                $choiceList = ChoiceList::create([
                    'list_name' => $listName,
                    'xlsform_template_id' => $this->xlsformTemplate->id,
                ]);

                $choices = $itemList->map(function (Collection $item) use ($labelColumns, $choiceList) {


                    // Create the choice list entry (1 per row)
                    $choiceListEntry = $choiceList->choiceListEntries()->create([
                        'name' => $item['name'],
                        // TODO: include other column data as properties
                    ]);


                    // Create the language strings (1 per row * label_column)
                    foreach ($labelColumns as $templateLanguageId => $labelColumn) {

                        $stringType = Str::before($labelColumn, '::');

                        $choiceListEntry->languageStrings()->create([
                            'xlsform_template_language_id' => $templateLanguageId,
                            'language_string_type_id' => LanguageStringType::where('name', $stringType)->first()->id,
                            'text' => $item[$labelColumn],
                        ]);
                    }

                });
            });

    }

}
