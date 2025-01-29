<?php

namespace App\Filament\Admin\Resources\XlsformTemplateResource\Pages;

use App\Filament\Admin\Resources\XlsformTemplateResource;
use Stats4sd\FilamentOdkLink\Filament\OdkAdmin\Resources\XlsformTemplateResource\Pages\ListXlsformTemplates as OdkListXlsformTemplates;


class ListXlsformTemplates extends OdkListXlsformTemplates
{
    protected static string $resource = XlsformTemplateResource::class;
}
