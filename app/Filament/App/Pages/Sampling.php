<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;

class Sampling extends Page
{
    protected static string $view = 'filament.app.pages.sampling';

    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $title = 'Sampling';

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\App\Pages\SurveyDashboard::getUrl() => 'Survey Dashboard',
            static::getUrl() => static::getTitle(),
        ];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function markCompleteAction(): Action
    {
        return Action::make('markComplete')
            ->label('MARK AS COMPLETE')
            ->extraAttributes(['class' => 'buttona mx-4 inline-block'])
            ->action(function () {
                $team = Team::find(auth()->user()->latestTeam->id);
                $team->sampling_progress = 'complete';
                $team->save();
            });
    }
}
