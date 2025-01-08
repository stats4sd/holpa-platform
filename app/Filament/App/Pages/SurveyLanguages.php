<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use App\Services\HelperService;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class SurveyLanguages extends Page
{
    protected static string $view = 'filament.app.pages.survey-languages';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Survey Languages';

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
                HelperService::getSelectedTeam()->update([
                    'languages_complete' => 1,
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
                HelperService::getSelectedTeam()->update([
                    'languages_complete' => 0,
                ]);

                $this->dispatch('refreshPage');
            });
    }

}
