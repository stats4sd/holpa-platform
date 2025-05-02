<?php

namespace App\Filament\App\Pages\Pilot;

use App\Filament\App\Pages\SurveyDashboard;
use App\Models\Team;
use App\Services\HelperService;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Livewire\Attributes\Url;

class PilotIndex extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.pilot.pilot-index';

    protected ?string $heading = 'Survey Testing - Pilot and Enumerator Training';

    #[Url]
    public string $tab = 'xlsforms';
    public Team $team;

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            PilotIndex::getUrl() => 'Localisation: Pilot',
        ];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(): void
    {
        $this->team = HelperService::getCurrentOwner();
    }
}
