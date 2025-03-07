<?php

namespace App\Filament\App\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use App\Services\HelperService;

class Lisp extends Page
{
    protected static string $view = 'filament.app.pages.lisp';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Localisation: LISP';

    protected $listeners = ['refreshPage' => '$refresh'];

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
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
                HelperService::getCurrentOwner()->update([
                    'lisp_complete' => 1,
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
                HelperService::getCurrentOwner()->update([
                    'lisp_complete' => 0,
                ]);

                $this->dispatch('refreshPage');
            });
    }
}
