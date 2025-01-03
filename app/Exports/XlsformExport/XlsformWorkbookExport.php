<?php

namespace App\Exports\XlsformExport;

use App\Models\Team;
use App\Models\XlsformLanguages\LanguageStringType;
use App\Models\XlsformLanguages\Locale;
use App\Models\Xlsforms\Xlsform;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class XlsformWorkbookExport implements WithMultipleSheets
{

    public Collection $xlsformTemplateLanguages;
    public Collection $languageStringTypes;
    public Collection $languages;

    public function __construct(public Xlsform $xlsform)
    {

        // get the xlsformTemplateLanguages that the team has chosen

        /** @var Team $team */
        $team = $xlsform->owner;

        /** @var Collection<Locale> $locales */
        $this->languages = $team->locales->map(fn(Locale $locale) => $locale->language);

        //$xlsformTemplate = $xlsform->xlsformTemplate;

//        $this->xlsformTemplateLanguages = $locales->map(fn(Locale $locale) => $locale
//            ->xlsformTemplateLanguages
//            //->filter(fn($xlsformTemplateLanguage) => $xlsformTemplate->xlsformTemplateLanguages->contains('id', $xlsformTemplateLanguage->id))
//        )
//        ->flatten();

        // get the language string types once to avoid multiple queries
        $this->languageStringTypes = LanguageStringType::all();

    }

    public function sheets(): array
    {
        return [
            new XlsformSurveyExport($this->xlsform, $this->languages, $this->languageStringTypes),
            new XlsformChoicesExport($this->xlsform, $this->languages, $this->languageStringTypes),
            new XlsformSettingsExport($this->xlsform),
        ];

    }
}
