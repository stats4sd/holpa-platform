<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\FarmResource\Pages;

use Filament\Actions;
use App\Imports\FarmImport;
use Filament\Forms\Components\Wizard;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\App\Pages\SurveyDashboard;
use App\Filament\Tables\Actions\ImportFarmsAction;
use App\Filament\App\Pages\SurveyLocations\SurveyLocationsIndex;
use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource;
use Filament\Forms\Components\Actions as ComponentActions;
use Filament\Forms\Components\Actions\Action as ComponentAction;

class ListFarms extends ListRecords
{
    protected static string $resource = FarmResource::class;

    protected ?string $heading = 'Survey locations';
    protected ?string $subheading = 'List of farms';

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            SurveyLocationsIndex::getUrl() => 'Survey locations',
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
            // use CreateFarm to show wizard temporary, move wizard to a new page later
            // Actions\CreateAction::make(),

            Actions\CreateAction::make()
                ->label('Import Locations and Farm List'),

            ImportFarmsAction::make()
                ->use(FarmImport::class)
                ->color('primary')
                ->label('Import Farm list'),

        ];
    }
}
