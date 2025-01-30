<?php

namespace App\Filament\App\Pages;

use App\Filament\Actions\ExportDataAction;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Stats4sd\FilamentOdkLink\Services\HelperService;

class DataAnalysis extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string $view = 'filament.app.pages.data-analysis';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Data Analysis';

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

    public function exportDataAction(): Action
    {
        return ExportDataAction::make('exportData')
            ->label('Export Data')
            ->extraAttributes(['class' => 'buttona']);
    }

    public function markCompleteAction(): Action
    {
        return Action::make('markComplete')
            ->label('MARK AS COMPLETE')
            ->extraAttributes(['class' => 'buttona mx-4 inline-block'])
            ->action(function () {
                $team = HelperService::getCurrentOwner();
                $team->data_analysis_progress = 'complete';
                $team->save();

                $this->dispatch('refreshPage');
            });
    }

    public function markIncompleteAction(): Action
    {
        return Action::make('markIncomplete')
            ->label('MARK AS INCOMPLETE')
            ->extraAttributes(['class' => 'buttona block md:inline-block mb-6 md:mb-0 max-w-sm mx-auto'])
            ->action(function () {
                $team = HelperService::getCurrentOwner();
                $team->data_analysis_progress = 'not_started';
                $team->save();

                $this->dispatch('refreshPage');
            });
    }
}
