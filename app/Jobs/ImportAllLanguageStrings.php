<?php

namespace App\Jobs;

use App\Imports\XlsformTemplate\XlsformTemplateLanguageStringImport;
use App\Models\Interfaces\WithXlsformFile;
use App\Models\XlsformTemplates\XlsformTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;

class ImportAllLanguageStrings implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string          $filePath,
        public WithXlsformFile $xlsformTemplate,
        public Collection      $translatableHeadings,
        public Collection      $importedTemplateLanguages,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // import the language strings for all the translatable headings in the surveys tab;
        foreach ($this->translatableHeadings as $sheet => $headings) {
            foreach ($headings as $heading) {
                (new XlsformTemplateLanguageStringImport($this->xlsformTemplate, $heading, $sheet))->queue($this->filePath)
                    ->chain([
                        new MarkTemplateLanguagesAsNeedingUpdate($this->xlsformTemplate, $this->importedTemplateLanguages),
                        new FinishLanguageStringImport($this->xlsformTemplate, $heading),
                    ]);
            }
        }
    }
}
