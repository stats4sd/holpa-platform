<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource\Pages;

use Illuminate\Support\Str;
use App\Imports\LocationImport;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Tables\Actions\ImportLocationsAction;
use App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource;

class ViewLocationLevel extends ViewRecord
{
    protected static string $resource = LocationLevelResource::class;

    public function getTitle(): string|Htmlable
    {
        return Str::of($this->record->name)->plural()->title();
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
