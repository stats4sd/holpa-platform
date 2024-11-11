<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class SurveyLanguages extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.survey-languages';

    protected static bool $shouldRegisterNavigation = false;
}
