<?php

namespace App\Jobs;

use App\Models\Interfaces\WithXlsformFile;
use App\Models\XlsformTemplateModule;
use App\Models\XlsformTemplates\SurveyRow;
use App\Models\XlsformTemplates\XlsformTemplate;
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

        // for modules, set the parent
        if ($this->xlsformTemplate instanceof XlsformTemplateModule) {
            $this->xlsformTemplate->surveyRows()
                ->get()
                ->each(function (SurveyRow $surveyRow) {
                    $parent = SurveyRow::where('name', $surveyRow->name)
                        ->where('type', $surveyRow->type)
                        ->where('template_id', $this->xlsformTemplate->xlsformTemplate->id)
                        ->where('template_type', XlsformTemplate::class)
                        ->first();

                    if ($parent) {
                        $surveyRow->parent()->associate($parent->id);
                    }
                });
        }
    }
}
