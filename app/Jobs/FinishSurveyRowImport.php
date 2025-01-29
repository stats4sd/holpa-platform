<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;

class FinishSurveyRowImport implements ShouldQueue
{
    use Queueable;

    public function __construct(public XlsformModuleVersion $xlsformModuleVersion)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->xlsformModuleVersion->surveyRows()
            ->update(['updated_during_import' => false]);

    }
}
