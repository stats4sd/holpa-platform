<?php

namespace App\Filament\Admin\Resources\XlsformTemplateModuleResource\Pages;

use App\Filament\Admin\Resources\XlsformModuleVersionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageXlsformModuleVersion extends ManageRecords
{
    protected static string $resource = XlsformModuleVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
