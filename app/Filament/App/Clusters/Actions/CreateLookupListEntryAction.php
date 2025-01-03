<?php

namespace App\Filament\App\Clusters\Actions;

use App\Models\XlsformLanguages\Locale;
use App\Models\Xlsforms\ChoiceListEntry;
use App\Services\HelperService;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class CreateLookupListEntryAction extends CreateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $locales = HelperService::getSelectedTeam()?->locales;

        $labelFields = $locales->map(function (Locale $locale) {
            return TextInput::make('label_' . $locale->language_id)
                ->label('label::' . $locale->language_label);
        });

        $this->form([
            TextInput::make('name')->required(),
            ...$labelFields->toArray(),
        ]);

        // add the current team as owner
        if (Filament::hasTenancy()) {
            $this->mutateFormDataUsing(
                function (array $data): array {

                    $locales = HelperService::getSelectedTeam()?->locales;
                    return collect($data)
                        // don't include the 'label' entries - we will create languageStrings after creating the ChoiceListEntry
                        ->filter(fn($value, $key) => !Str::startsWith($key, 'label_'))
                        ->put('owner_id', Filament::getTenant()->id)
                        ->put('owner_type', get_class(Filament::getTenant()))
                        ->toArray();
                })
                ->after(function (ChoiceListEntry $record, array $data) {
                    $locales = HelperService::getSelectedTeam()?->locales;

                    dd($data);

                    $languageStrings = $locales->map(function (Locale $locale) use ($record, $data) {
                        $xlsformTemplateLanguage = $record->xlsformTemplate->xlsformTemplateLanguages()->where('language_id', $locale->language_id)->first();

                        return [
                           'xlsform_template_language_id' => $xlsformTemplateLanguage->id,
                            'language_string_type_id' => 1, // hardcoded to labels. TODO: fix;
                            'text' => $data,
                        ];
                    });
            });
        }

    }
}
