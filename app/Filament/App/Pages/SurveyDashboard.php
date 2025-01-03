<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class SurveyDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.app.pages.survey-dashboard';

    protected static ?string $navigationLabel = 'Survey Dashboard';

    protected static ?string $title = '';

    public $team;

    public function mount(): void
    {
        $this->team = Team::find(auth()->user()->latestTeam->id);
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

}
