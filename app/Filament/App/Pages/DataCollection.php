<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class DataCollection extends Page
{
    protected static string $view = 'filament.app.pages.data-collection';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Data Collection';

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
}
