<?php

namespace App\Filament\Admin\Resources\XlsformTemplateResource\Pages;

use App\Filament\Admin\Resources\XlsformTemplateResource;
use Stats4sd\FilamentOdkLink\Filament\Resources\XlsformTemplateResource\Pages\EditXlsformTemplate as OdkLinkEditXlsformTemplate;

class EditXlsformTemplate extends OdkLinkEditXlsformTemplate
{
    protected static string $resource = XlsformTemplateResource::class;
}