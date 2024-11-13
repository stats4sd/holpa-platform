<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class Pilot extends Page
{
    protected static string $view = 'filament.app.pages.pilot';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Pilot';

    protected static ?string $title = '';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
