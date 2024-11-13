<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class SurveyLanguages extends Page
{
    protected static string $view = 'filament.app.pages.survey-languages';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Survey Languages';

    protected static ?string $title = '';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
