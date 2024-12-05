<?php

namespace App\Listeners;

use App\Imports\XlsformTemplate\XlsformTemplateWorkbookImport;
use App\Imports\XlsformTemplate\XlsformTemplateLanguageStringImport;
use App\Jobs\FinishImport;
use App\Models\LanguageString;
use App\Models\SurveyRow;
use App\Services\XlsformTranslationHelper;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

class HandleXlsformTemplateAdded
{
    public function handle(MediaHasBeenAddedEvent $event)
    {
        Log::info('MediaHasBeenAdded event fired!');
        $model = $event->media->model;

        if ($model instanceof \App\Models\XlsformTemplate) {
            $filePath = $event->media->getPath();

            if ($filePath) {

                $translatableHeadings = (new XlsformTranslationHelper())->getTreanslatableColumnsFromFile($filePath);

                // Set up XlsformTemplateLanguages for each language in the XLSform workbook;
                foreach ($translatableHeadings as $sheet => $headings) {
                    $model->setXlsformTemplateLanguages($headings);
                }

                // TODO: add validation check to make sure all names are unique in Survey + choices sheet...

                // Import the XLSform workbook to survey rows and choice list entries;
                (new XlsformTemplateWorkbookImport($model, $translatableHeadings))->queue($filePath);

                // import the language strings for all the translatable headings in the surveys tab;
                foreach ($translatableHeadings as $sheet => $headings) {
                    foreach ($headings as $heading) {
                        (new XlsformTemplateLanguageStringImport($model, $heading, $sheet))->queue($filePath)
                        ->chain([
                            new FinishImport($model,LanguageString::class, $heading)
                        ]);
                    }
                }


            } else {
                Log::error('No file path found for media in collection "xlsform_file" for model ID: ' . $model->id);
            }
        }
    }
}
