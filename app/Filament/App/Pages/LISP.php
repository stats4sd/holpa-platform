<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class LISP extends Page
{
    protected static string $view = 'filament.app.pages.lisp';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'LISP';

    protected static ?string $title = '';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
