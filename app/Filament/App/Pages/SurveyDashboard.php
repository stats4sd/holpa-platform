<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use App\Services\HelperService;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class SurveyDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.app.pages.survey-dashboard';

    protected static ?string $navigationLabel = 'Survey Dashboard';

    protected static ?string $title = '';

    public Team $team;

    public function mount(): void
    {
        $this->team = HelperService::getSelectedTeam();
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

}
