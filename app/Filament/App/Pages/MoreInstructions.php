<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class MoreInstructions extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.more-instructions';

    protected static bool $shouldRegisterNavigation = false;
}
