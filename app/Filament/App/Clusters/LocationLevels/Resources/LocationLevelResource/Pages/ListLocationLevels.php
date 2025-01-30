<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource\Pages;

use App\Filament\App\Pages\Sampling;
use App\Filament\App\Pages\SurveyDashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource;

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
            Sampling::getUrl() => 'Survey Locations',
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
