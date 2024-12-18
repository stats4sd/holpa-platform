<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\FarmResource\Pages;

use Filament\Actions;
use App\Imports\FarmImport;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Tables\Actions\ImportFarmsAction;
use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource;

class ListFarms extends ListRecords
{
    protected static string $resource = FarmResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            FarmResource\Widgets\FarmListHeaderWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportFarmsAction::make()
                ->use(FarmImport::class)
                ->color('primary')
                ->label('Import Farm list'),
        ];
    }
}
