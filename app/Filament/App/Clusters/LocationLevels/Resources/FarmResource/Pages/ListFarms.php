<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\FarmResource\Pages;

use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource;
use App\Filament\App\Pages\SurveyDashboard;
use App\Filament\App\Pages\SurveyLocations\SurveyLocationsIndex;
use App\Filament\Tables\Actions\ImportFarmsAction;
use App\Imports\FarmImport;
use Filament\Actions;
use Filament\Forms\Components\Wizard;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Actions as ComponentActions;
use Filament\Forms\Components\Actions\Action as ComponentAction;

class ListFarms extends ListRecords
{
    protected static string $resource = FarmResource::class;

    protected ?string $heading = 'Survey locations';

    // protected ?string $subheading = 'List of farms';

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

            // add a button to divert to import custom page
            Actions\Action::make('import')
                ->label('Import Locations and Farm List')
                ->url('farms/import'),

            ImportFarmsAction::make()
                ->use(FarmImport::class)
                ->color('primary')
                ->label('Import Farm list'),
        ];
    }
}
