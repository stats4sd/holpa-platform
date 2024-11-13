<?php

namespace App\Listeners;

use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\XlsformTemplateSurveyImport;

class HandleXlsformTemplateAdded
{
    public function handle(MediaHasBeenAddedEvent $event)
    {
        Log::info('MediaHasBeenAdded event fired!');
        $model = $event->media->model;

        if ($model instanceof \App\Models\XlsformTemplate) {
            $filePath = $event->media->getPath();

            if ($filePath) {
                Excel::import(new XlsformTemplateSurveyImport($model), $filePath);
            } else {
                Log::error('No file path found for media in collection "xlsform_file" for model ID: ' . $model->id);
            }
        }
    }
}
