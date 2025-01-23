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

    protected ?string $heading = 'Survey locations';
    protected ?string $subheading = 'List of farms';

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\App\Pages\SurveyDashboard::getUrl() => 'Survey Dashboard',
            \App\Filament\App\Pages\Sampling::getUrl() => 'Survey locations',
            static::getUrl() => static::getTitle(),
        ];
    }

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
