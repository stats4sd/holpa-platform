<?php

namespace App\Filament\Admin\Resources\XlsformTemplateResource\Pages;

use App\Filament\Admin\Resources\XlsformTemplateResource;
use Stats4sd\FilamentOdkLink\Filament\OdkAdmin\Resources\XlsformTemplateResource\Pages\EditXlsformTemplate as OdkEditXlsformTemplate;

class EditXlsformTemplate extends OdkEditXlsformTemplate
{
    protected static string $resource = XlsformTemplateResource::class;

    // return empty array, so that there is no relation manager showed in Edit page
    public function getRelationManagers(): array
    {
        return [];
    }
}
