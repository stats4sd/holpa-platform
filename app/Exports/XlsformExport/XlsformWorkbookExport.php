<?php

namespace App\Exports\XlsformExport;

use App\Models\LanguageStringType;
use App\Models\Locale;
use App\Models\Team;
use App\Models\Xlsforms\Xlsform;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class XlsformWorkbookExport implements WithMultipleSheets
{

    public Collection $xlsformTemplateLanguages;
    public Collection $languageStringTypes;

    public function __construct(public Xlsform $xlsform)
    {

        // get the xlsformTemplateLanguages that the team has chosen

        /** @var Team $team */
        $team = $xlsform->owner;

        /** @var Collection<Locale> $locales */
        $locales = $team->locales;

        $xlsformTemplate = $xlsform->xlsformTemplate;

        $this->xlsformTemplateLanguages = $locales->map(fn(Locale $locale) => $locale
            ->xlsformTemplateLanguages
            ->filter(fn($xlsformTemplateLanguage) => $xlsformTemplate->xlsformTemplateLanguages->contains('id', $xlsformTemplateLanguage->id))
        )
        ->flatten();

        // get the language string types once to avoid multiple queries
        $this->languageStringTypes = LanguageStringType::all();

        ray($this->xlsformTemplateLanguages);
        ray($this->languageStringTypes);

    }

    public function sheets(): array
    {
        return [
            new XlsformSurveyExport($this->xlsform, $this->xlsformTemplateLanguages, $this->languageStringTypes),
            new XlsformChoicesExport($this->xlsform, $this->xlsformTemplateLanguages, $this->languageStringTypes),
            new XlsformSettingsExport($this->xlsform),
        ];

    }
}
