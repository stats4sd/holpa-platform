<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class Lisp extends Page
{
    protected static string $view = 'filament.app.pages.lisp';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Localisation: LISP';

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
                $team = Team::find(auth()->user()->latestTeam->id);
                $team->lisp_progress = 'complete';
                $team->save();

                $this->dispatch('refreshPage');
            });
    }

    public function markIncompleteAction(): Action
    {
        return Action::make('markIncomplete')
            ->label('MARK AS INCOMPLETE')
            ->extraAttributes(['class' => 'buttona mx-4 inline-block'])
            ->action(function () {
                $team = Team::find(auth()->user()->latestTeam->id);
                $team->lisp_progress = 'not_started';
                $team->save();

                $this->dispatch('refreshPage');
            });
    }
}
