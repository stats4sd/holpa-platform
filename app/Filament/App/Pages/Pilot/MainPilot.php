<?php

namespace App\Filament\App\Pages\Pilot;

use App\Filament\App\Pages\SurveyDashboard;
use App\Models\Team;
use App\Services\HelperService;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Livewire\Attributes\Url;

class MainPilot extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.pilot.main-pilot';

    protected ?string $heading = 'Survey Testing - Pilot and Enumerator Training';

    protected ?string $subheading = 'Test with enumerators; pilot with real farmers';

    #[Url]
    public string $tab = 'xlsforms';

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            PilotIndex::getUrl() => 'Pilot',
            static::getUrl() => static::getTitle(),
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

    public function getRecord(): Team
    {
        return HelperService::getCurrentOwner();
    }
}
