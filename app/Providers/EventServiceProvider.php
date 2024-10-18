<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MediaHasBeenAddedEvent::class => [
            \App\Listeners\HandleXlsformTemplateAdded::class,
        ],
    ];
}
