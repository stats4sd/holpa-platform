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

class DataAnalysis extends Page implements HasActions, HasForms
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string $view = 'filament.app.pages.data-analysis';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Data Analysis';

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

    public function exportAction(): Action
    {
        return ExportDataAction::make('export');
    }
}
