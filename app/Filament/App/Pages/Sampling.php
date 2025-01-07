<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class Sampling extends Page
{
    protected static string $view = 'filament.app.pages.sampling';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Survey Locations';

    protected $listeners = ['refreshPage' => '$refresh'];

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
                Team::find(auth()->user()->latestTeam->id)->update([
                    'sampling_complete' => 1,
                ]);

                $this->dispatch('refreshPage');
            });
    }

    public function markIncompleteAction(): Action
    {
        return Action::make('markIncomplete')
            ->label('MARK AS INCOMPLETE')
            ->extraAttributes(['class' => 'buttona mx-4 inline-block'])
            ->action(function () {
                Team::find(auth()->user()->latestTeam->id)->update([
                    'sampling_complete' => 0,
                ]);

                $this->dispatch('refreshPage');
            });
    }
}
