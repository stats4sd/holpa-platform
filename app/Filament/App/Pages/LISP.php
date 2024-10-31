<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class LISP extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.lisp';

    protected static bool $shouldRegisterNavigation = false;
}
