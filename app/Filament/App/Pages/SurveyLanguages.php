<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class SurveyLanguages extends Page
{
    protected static string $view = 'filament.app.pages.survey-languages';

    protected static bool $shouldRegisterNavigation = false;

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\App\Pages\SurveyDashboard::getUrl() => 'Survey Dashboard',
            static::getUrl() => static::getTitle(),
        ];
    }

    protected static ?string $title = 'Context: Survey Languages';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
