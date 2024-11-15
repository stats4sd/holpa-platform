<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use App\Filament\App\Pages\SurveyDashboard;

class LispIndicators extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.lisp-indicators';

    protected static ?string $title = 'Localisation: LISP';
    protected ?string $subheading = 'Customise indicators';

    protected static bool $shouldRegisterNavigation = false;

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            LISP::getUrl() => 'Localisation: LISP',
            static::getUrl() => 'Customise indicators',
        ];
    }
    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
