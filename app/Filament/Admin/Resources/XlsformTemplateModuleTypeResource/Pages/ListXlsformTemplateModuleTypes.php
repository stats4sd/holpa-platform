<?php

namespace App\Filament\Admin\Resources\XlsformTemplateModuleTypeResource\Pages;

use App\Filament\Admin\Resources\XlsformTemplateModuleTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListXlsformTemplateModuleTypes extends ListRecords
{
    protected static string $resource = XlsformTemplateModuleTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
