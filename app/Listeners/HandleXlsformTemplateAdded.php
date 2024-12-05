<?php

namespace App\Listeners;

use App\Imports\XlsformTemplate\XlsformTemplateWorkbookImport;
use App\Imports\XlsformTemplate\XlsformTemplateWorkbookLanguageStringImport;
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
                $model->setXlsformTemplateLanguages($translatableHeadings);

                // Import the XLSform workbook to survey rows and choice list entries;
                (new XlsformTemplateWorkbookImport($model, $translatableHeadings))->queue($filePath);
                (new XlsformTemplateWorkbookLanguageStringImport($model, $translatableHeadings))->queue($filePath);


            } else {
                Log::error('No file path found for media in collection "xlsform_file" for model ID: ' . $model->id);
            }
        }
    }
}
