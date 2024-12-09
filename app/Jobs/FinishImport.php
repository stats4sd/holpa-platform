<?php

namespace App\Jobs;

use App\Models\XlsformTemplate;
use App\Services\XlsformTranslationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\ClassString;

class FinishImport implements ShouldQueue
{
    use Queueable;

    public function __construct(public XlsformTemplate $xlsformTemplate, public string $class, public ?string $heading = null)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // When deleting entries based on specific columns (language strings), we need to filter by the templateLanguage + string type
        if ($this->heading) {

            // need to filter the class by the templateLanguage + string  type
            $xlsformTranslationHelper = new XlsformTranslationHelper();

            $language = $xlsformTranslationHelper->getLanguageFromColumnHeader($this->heading);
            $languageStringType = $xlsformTranslationHelper->getLanguageStringTypeFromColumnHeader($this->heading);

            $xlsformTemplateLanguage = $this->xlsformTemplate->xlsformTemplateLanguages()
                ->where('language_id', $language->id)
                ->whereHas('locale', fn(Builder $query) => $query->where('description', null))
                ->first();

            $this->class::where('xlsform_template_language_id', $xlsformTemplateLanguage->id)
                ->where('language_string_type_id', $languageStringType->id)
                ->update(['updated_during_import' => false]);
        } else {

            // otherwise just filter by the template + update all entries
            $this->class::where('xlsform_template_id', $this->xlsformTemplate->id)
                ->update(['updated_during_import' => false]);
        }
    }
}
