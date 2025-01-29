<?php

namespace App\Jobs;

use App\Imports\XlsformTemplate\XlsformTemplateLanguageStringImport;
use App\Models\Xlsforms\XlsformModuleVersion;
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
        public string               $filePath,
        public XlsformModuleVersion $xlsformModuleVersion,
        public Collection           $translatableHeadings,
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
                (new XlsformTemplateLanguageStringImport($this->xlsformModuleVersion, $heading, $sheet))->queue($this->filePath)
                    ->chain([
                        new FinishLanguageStringImport($this->xlsformModuleVersion, $heading),
                    ]);
            }
        }
    }
}
