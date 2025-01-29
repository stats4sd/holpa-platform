<?php

namespace App\Exports;

use App\Models\XlsformLanguages\XlsformModuleVersionLocale;
use App\Models\Xlsforms\ChoiceList;
use App\Models\Xlsforms\ChoiceListEntry;
use App\Models\Xlsforms\LanguageString;
use App\Models\Xlsforms\Xlsform;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithXlsformDrafts;

// ** This export ONLY works for HOLPA right now, as it uses a bunch of App\Models references etc. This will be reconciled later... */
class ChoiceListModelsExport implements FromCollection, WithHeadings, WithStrictNullComparison
{
    public Collection $entries;

    // by default, we use the dataset variables as the columns. If you want to specify columns, you can pass them in as an array.
    public function __construct(
        public ChoiceList $choiceList,
        public WithXlsformDrafts | Xlsform $xlsform
    ) {

        // get the template languages for the form chosen by the team
        if ($xlsform->xlsformTemplate) {
            $xlsformTemplateLanguages = $xlsform->xlsformTemplate->xlsformTemplateLanguages;
        } else {
            $xlsformTemplateLanguages = $xlsform->xlsformTemplateLanguages;
        }

        $xlsformTemplateLanguages = $xlsformTemplateLanguages
            ->filter(fn (XlsformModuleVersionLocale $xlsformTemplateLanguage) => $xlsform->owner->locales->contains('id', $xlsformTemplateLanguage->locale->id));

        $this->entries = $choiceList->choiceListEntries
            // may not need explicit filter when running on front-end with Filament Tenancy, but won't hurt
            ->filter(fn (ChoiceListEntry $choiceListEntry) => $choiceListEntry->owner_id === $xlsform->owner->id || $choiceListEntry->owner_id === null)
            ->mapWithKeys(function (ChoiceListEntry $choiceListEntry) use ($xlsformTemplateLanguages) {

                $labelColumns = $xlsformTemplateLanguages->mapWithKeys(function (XlsformModuleVersionLocale $xlsformTemplateLanguage) use ($choiceListEntry) {
                    return $choiceListEntry
                        ->languageStrings
                        ->filter(fn (LanguageString $languageString) => $languageString->xlsformTemplateLanguage->id === $xlsformTemplateLanguage->id)
                        ->mapWithKeys(fn (LanguageString $languageString) => [
                            "{$languageString->languageStringType->name}_{$xlsformTemplateLanguage->language->iso_alpha2}" => $languageString->text,
                        ]);
                });

                if (isset($choiceListEntry->choiceList->properties['extra_properties'])) {
                    $propertyColumns = collect($choiceListEntry->choiceList->properties['extra_properties'])->mapWithKeys(fn ($property) => [
                        $property['name'] => $choiceListEntry->properties[$property['name']] ?? null]);
                } else {
                    $propertyColumns = collect();
                }

                return [
                    $choiceListEntry->id => collect([
                        'id' => $choiceListEntry->id,
                        'name' => $choiceListEntry->name,
                        ...$labelColumns,
                        ...$propertyColumns,
                    ]),
                ];

            });
    }

    public function collection(): Collection
    {
        return $this->entries;
    }

    public function headings(): array
    {
        return $this->entries->first()->keys()->toArray();
    }
}
