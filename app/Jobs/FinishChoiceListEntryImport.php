<?php

namespace App\Jobs;

use App\Models\XlsformTemplates\XlsformTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FinishChoiceListEntryImport implements ShouldQueue
{
    use Queueable;

    public function __construct(public XlsformTemplate $xlsformTemplate)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->xlsformTemplate->choiceListEntries()
            ->update(['updated_during_import' => false]);

    }
}
