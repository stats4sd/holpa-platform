<?php

namespace App\Jobs;

use App\Models\Interfaces\WithXlsformFile;
use App\Services\XlsformTranslationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Queue\Queueable;

class FinishLanguageStringImport implements ShouldQueue
{
    use Queueable;

    public function __construct(public WithXlsformFile $xlsformTemplate, public string $heading)
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

            $xlsformTemplateLanguage = $this->xlsformTemplate->xlsformTemplateLanguages()
                ->where('language_id', $language->id)
                ->whereHas('locale', fn(Builder $query) => $query->where('description', null))
                ->first();

            $xlsformTemplateLanguage->languageStrings()
                ->where('language_string_type_id', $languageStringType->id)
                ->update(['updated_during_import' => false]);

    }
}
