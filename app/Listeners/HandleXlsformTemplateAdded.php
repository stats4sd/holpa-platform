<?php

namespace App\Listeners;

use App\Imports\XlsformTemplateUnpacker;
use App\Models\XlsformTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

class HandleXlsformTemplateAdded
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MediaHasBeenAddedEvent $event): void
    {
        $model = $event->media->model;

        if($model instanceof XlsformTemplate) {
            $filePath = $event->media->getPath();

        Excel::import(new XlsformTemplateUnpacker($model), $filePath);

        }
    }
}
