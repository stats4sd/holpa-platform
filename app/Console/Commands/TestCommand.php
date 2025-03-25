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

        foreach (XlsformTemplate::where('id', 1)->get() as $model) {

            /** @var Collection<SurveyRow> $surveyRows */
            $surveyRows = $model->surveyRows()->orderBy('row_number')->get();
            $path = '/';
            $repeatPaths = collect(); // for nested repeats

            foreach ($surveyRows as $surveyRow) {
                // if begin group or begin repeat; append to path

                switch ($surveyRow->type) {
                    case 'begin group':
                    case 'begin_group':
                        $path .= $surveyRow->name . '/';
                        $surveyRow->update([
                            'path' => $path,
                            'repeat_group_path' => $repeatPaths->last() ?? null,
                        ]);
                        break;

                    case 'end group':
                    case 'end_group':
                        $path = substr($path, 0, -1);
                        $path = substr($path, 0, strrpos($path, '/') + 1);
                        $surveyRow->update([
                            'path' => $path,
                            'repeat_group_path' => $repeatPaths->last() ?? null,
                        ]);
                        break;

                    case 'begin repeat':
                    case 'begin_repeat':
                        $repeatPaths->push($path . $surveyRow->name . '/');
                        $path = '/';
                        $surveyRow->update([
                            'path' => $path,
                            'repeat_group_path' => $repeatPaths->last() ?? null,
                        ]);
                        break;

                    case 'end repeat':
                    case 'end_repeat':
                        $path = $repeatPaths->pop();

                        $path = substr($path, 0, -1);
                        $path = substr($path, 0, strrpos($path, '/') + 1);

                        $surveyRow->update([
                            'path' => $path,
                            'repeat_group_path' => $repeatPaths->last() ?? null,
                        ]);
                        break;

                    default:
                        $surveyRow->update([
                            'path' => $path . $surveyRow->name,
                            'repeat_group_path' => $repeatPaths->last() ?? null,
                        ]);
                        break;
                }


            }
        }

    }
}
