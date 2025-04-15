<?php

namespace App\Filament\App\Pages\Lisp;

use App\Filament\App\Pages\SurveyDashboard;
use App\Services\HelperService;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class LispIndex extends Page
{
    protected static string $view = 'filament.app.pages.lisp.lisp-index';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Localisation: LISP';

    protected ?string $summary = 'The local indicator selection process (LISP) involves conducting a workshop with local farmers and stakeholders to brainstorm and prioritise a set of local indicators to include in the HOLPA tool.';


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
            'summary' => $this->summary,
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
