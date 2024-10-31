<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class DataCollection extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.data-collection';

    protected static bool $shouldRegisterNavigation = false;
}
