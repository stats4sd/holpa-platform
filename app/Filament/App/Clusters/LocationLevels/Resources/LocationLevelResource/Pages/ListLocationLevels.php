<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource;
use Filament\Support\Enums\MaxWidth;

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
            \App\Filament\App\Pages\SurveyDashboard::getUrl() => 'Survey Dashboard',
            \App\Filament\App\Pages\Sampling::getUrl() => 'Survey Locations',
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
