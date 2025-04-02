<?php

namespace App\Filament\App\Pages\DataCollection;

use App\Filament\App\Pages\SurveyDashboard;
use App\Services\HelperService;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class DataCollectionIndex extends Page
{
    protected static string $view = 'filament.app.pages.data-collection';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Data Collection';

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
                $team = HelperService::getCurrentOwner();
                $team->data_collection_progress = 'complete';
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
                $team = HelperService::getCurrentOwner();
                $team->data_collection_progress = 'not_started';
                $team->save();

                $this->dispatch('refreshPage');
            });
    }

}
