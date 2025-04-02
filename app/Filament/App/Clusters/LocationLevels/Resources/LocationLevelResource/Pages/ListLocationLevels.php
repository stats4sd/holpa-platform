<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource\Pages;

use App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource;
use App\Filament\App\Pages\SurveyDashboard;
use App\Filament\App\Pages\SurveyLocations\SurveyLocationsIndex;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocationLevels extends ListRecords
{
    protected static string $resource = LocationLevelResource::class;

    protected ?string $heading = 'Survey Locations';
    protected ?string $subheading = 'Manage hierarchy';

    protected static string $view = 'filament.app.pages.list-location-levels';

//    public function getMaxContentWidth(): MaxWidth|string|null
//    {
//        return MaxWidth::Full;
//    }

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            SurveyLocationsIndex::getUrl() => 'Survey Locations',
            static::getUrl() => static::getTitle(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
