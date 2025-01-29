<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\FarmResource\Pages;

use App\Filament\App\Pages\Sampling;
use App\Filament\App\Pages\SurveyDashboard;
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
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            Sampling::getUrl() => 'Survey locations',
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
