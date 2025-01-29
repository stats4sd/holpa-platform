<?php

namespace App\Jobs;

use App\Services\XlsformTranslationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;

class FinishLanguageStringImport implements ShouldQueue
{
    use Queueable;

    public function __construct(public XlsformModuleVersion $xlsformModuleVersion, public string $heading)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // need to filter the class by the templateLanguage + string  type
        $xlsformTranslationHelper = new XlsformTranslationHelper();

        $language = $xlsformTranslationHelper->getLanguageFromColumnHeader($this->heading);
        $languageStringType = $xlsformTranslationHelper->getLanguageStringTypeFromColumnHeader($this->heading);

        $this->xlsformModuleVersion
            ->surveyLanguageStrings()
            ->where('language_string_type_id', $languageStringType->id)
            ->where('locale_id', $language->defaultLocale->id)
            ->update(['language_strings.updated_during_import' => false]);

        $this->xlsformModuleVersion
            ->choiceListEntryLanguageStrings()
            ->where('language_string_type_id', $languageStringType->id)
            ->where('locale_id', $language->defaultLocale->id)
            ->update(['language_strings.updated_during_import' => false]);
    }
}
