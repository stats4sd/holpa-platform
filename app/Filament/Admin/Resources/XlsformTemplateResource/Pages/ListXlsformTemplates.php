<?php

namespace App\Filament\Admin\Resources\XlsformTemplateResource\Pages;

use App\Filament\Admin\Resources\XlsformTemplateResource;
use Stats4sd\FilamentOdkLink\Filament\Resources\XlsformTemplateResource\Pages\ListXlsformTemplates as OdkLinkListXlsformTemplates;

class ListXlsformTemplates extends OdkLinkListXlsformTemplates
{
    protected static string $resource = XlsformTemplateResource::class;
}
