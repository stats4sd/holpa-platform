<?php

namespace App\Jobs;

use App\Models\XlsformTemplateLanguage;
use App\Models\XlsformTemplates\XlsformTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;

class MarkTemplateLanguagesAsNeedingUpdate implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public XlsformTemplate $xlsformTemplate, public Collection $importedTemplateLanguages)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // if there are languageStrings where updated_during_import === true, we should  mark any TemplateLanguage *not* in the import as  needing an update.
        $needsUpdate = $this->xlsformTemplate->languageStrings()->where('updated_during_import', true)->count() > 0;

        if(!$needsUpdate) {
            return;
        }


        $this->xlsformTemplate
            ->xlsformTemplateLanguages
            ->filter(fn(XlsformTemplateLanguage $xlsformTemplateLanguage) => $this->importedTemplateLanguages->doesntContain('id', $xlsformTemplateLanguage->id))
            ->each(fn(XlsformTemplateLanguage $xlsformTemplateLanguage) => $xlsformTemplateLanguage->update(['needs_update' => true]));

    }
}
