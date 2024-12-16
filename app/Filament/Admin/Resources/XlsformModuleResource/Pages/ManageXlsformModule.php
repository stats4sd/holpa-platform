<?php

namespace App\Filament\Admin\Resources\XlsformTemplateModuleTypeResource\Pages;

use App\Filament\Admin\Resources\XlsformModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ManageXlsformModule extends ListRecords
{
    protected static string $resource = XlsformModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
