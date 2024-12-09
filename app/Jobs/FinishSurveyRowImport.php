<?php

namespace App\Jobs;

use App\Models\XlsformTemplate;
use App\Services\XlsformTranslationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\ClassString;

class FinishSurveyRowImport implements ShouldQueue
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
        $this->xlsformTemplate->surveyRows()
            ->update(['updated_during_import' => false]);
    }
}
