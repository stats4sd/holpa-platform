<?php

namespace App\Jobs;

use App\Models\Interfaces\WithXlsformFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FinishSurveyRowImport implements ShouldQueue
{
    use Queueable;

    public function __construct(public WithXlsformFile $xlsformTemplate)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->xlsformTemplate->surveyRows()
            ->update(['updated_during_import' => false]);

    }
}
