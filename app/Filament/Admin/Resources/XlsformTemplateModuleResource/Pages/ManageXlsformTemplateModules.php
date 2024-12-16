<?php

namespace App\Filament\Admin\Resources\XlsformTemplateModuleResource\Pages;

use App\Filament\Admin\Resources\XlsformTemplateModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageXlsformTemplateModules extends ManageRecords
{
    protected static string $resource = XlsformTemplateModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
