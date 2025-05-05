<?php

namespace App\Filament\App\Pages\DataCollection;

use App\Filament\App\Pages\SurveyDashboard;
use App\Models\SampleFrame\Location;
use App\Models\SampleFrame\LocationLevel;
use App\Models\Team;
use App\Services\HelperService;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class MonitorDataCollection extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.data-collection.monitor-data-collection';

    public Team $team;

    #[Url]
    public ?int $locationLevelId = null;

    #[Url]
    public ?array $tableFilters;

    public function mount(): void
    {
        $this->team = HelperService::getCurrentOwner();
    }

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            DatacollectionIndex::getUrl() => 'Data Collection',
            static::getUrl() => 'Monitor Data Collection',
        ];
    }

    public function showTable(string $locationLevelId)
    {
        if ($locationLevelId === 'farms') {
            $this->locationLevelId = null;
            // reset table filter url query param
            $this->tableFilters = [];
        } else {
            $this->locationLevelId = $locationLevelId;
            $this->tableFilters = [];
        }
    }

}
