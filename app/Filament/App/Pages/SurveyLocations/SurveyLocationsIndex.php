<?php

namespace App\Filament\App\Pages\SurveyLocations;

use App\Filament\App\Pages\SurveyDashboard;
use App\Services\HelperService;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class SurveyLocationsIndex extends Page
{
    protected static string $view = 'filament.app.pages.survey-locations.survey-locations-index';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Survey Locations';

    protected $listeners = ['refreshPage' => '$refresh'];

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            static::getUrl() => static::getTitle(),
        ];
    }

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('components.small-header', [
            'heading' => $this->getHeading(),
            'subheading' => $this->getSubheading(),
            'actions' => $this->getHeaderActions(),
            'breadcrumbs' => $this->getBreadcrumbs(),
        ]);
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
                HelperService::getCurrentOwner()->update([
                    'sampling_complete' => 0,
                ]);

                $this->dispatch('refreshPage');
            });
    }
}
