<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource\Pages;

use App\Filament\App\Pages\Sampling;
use App\Filament\App\Pages\SurveyDashboard;
use Illuminate\Support\Str;
use App\Imports\LocationImport;
use App\Services\HelperService;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Tables\Actions\ImportLocationsAction;
use App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource;

class ViewLocationLevel extends ViewRecord
{
    protected static string $resource = LocationLevelResource::class;

    protected ?string $heading = 'Survey locations';

    public function getSubheading(): string|Htmlable
    {
        return 'List of ' . Str::of($this->record->name)->plural()->title();
    }

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            Sampling::getUrl() => 'Survey locations',
            route('filament.app.location-levels.resources.location-levels.view', [
                'tenant' => HelperService::getSelectedTeam()->id,
                'record' => $this->record->slug
            ]) => Str::of($this->record->name)->plural(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            ImportLocationsAction::make()
                ->use(LocationImport::class)
            ->color('primary')
            ->label('Import ' . Str::of($this->record->name)->plural()),
        ];
    }

    public function getSubNavigation(): array
    {
        if (filled($cluster = static::getCluster())) {
            return $this->generateNavigationItems($cluster::getClusteredComponents());
        }

        return [];
    }
}
