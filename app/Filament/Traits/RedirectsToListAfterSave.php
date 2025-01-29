<?php

namespace App\Filament\Traits;

trait RedirectsToListAfterSave
{

    // redirect back to index unless that is impossible
    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }
}
