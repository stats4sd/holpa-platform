<?php

namespace App\Jobs;

use App\Models\Interfaces\WithXlsformFile;
use App\Models\Xlsforms\XlsformModuleVersion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
