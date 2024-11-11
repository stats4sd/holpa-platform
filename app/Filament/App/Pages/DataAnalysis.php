<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class DataAnalysis extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.data-analysis';

    protected static bool $shouldRegisterNavigation = false;
}
