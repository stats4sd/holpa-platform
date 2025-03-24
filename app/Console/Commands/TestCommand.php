<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use App\Models\SurveyData\FishUse;
use App\Models\SurveyData\FarmSurveyData;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For running test or one-time commands that do not need to be kept during development';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        foreach (XlsformTemplate::all() as $model) {

            /** @var Collection<SurveyRow> $surveyRows */
            $surveyRows = $model->surveyRows()->orderBy('row_number')->get();
            $path = '/';

            foreach ($surveyRows as $surveyRow) {
                // if begin group or begin repeat; append to path

                if ($surveyRow->type === 'begin group' || $surveyRow->type === 'begin repeat'
                    || $surveyRow->type === 'begin_group' || $surveyRow->type === 'begin_repeat'
                ) {
                    $path .= $surveyRow->name . '/';
                    $surveyRow->update(['path' => $path]);
                    continue;
                }

                if ($surveyRow->type === 'end group' || $surveyRow->type === 'end repeat'
                    || $surveyRow->type === 'end_group' || $surveyRow->type === 'end_repeat'
                ) {
                    // path is '/one/two/three/latest/'
                    // want to remove 'latest/'.

                    // delete the last '/' character from $path
                    $path = substr($path, 0, -1);

                    // delete the last segment from $path
                    $path = substr($path, 0, strrpos($path, '/') + 1);
                    $surveyRow->update(['path' => $path]);
                    continue;
                }

                $surveyRow->update(['path' => $path . $surveyRow->name]);


            }
        }

    }
}
