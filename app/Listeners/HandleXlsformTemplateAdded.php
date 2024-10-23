<?php

namespace App\Listeners;

use App\Imports\XlsformTemplateUnpacker;
use App\Models\XlsformTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\XlsformTemplateImport;

class HandleXlsformTemplateAdded
{
    public function handle(MediaHasBeenAddedEvent $event)
    {
        Log::info('MediaHasBeenAdded event fired!');
        $model = $event->media->model;


        if($model instanceof XlsformTemplate) {
            $filePath = $event->media->getPath();

            if ($filePath) {
                Excel::import(new XlsformTemplateImport($model->id), $filePath);
            } else {
                Log::error('No file path found for media in collection "xlsform_file" for model ID: ' . $model->id);
            }
        }
    }
}
