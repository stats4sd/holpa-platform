<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class Sampling extends Page
{
    protected static string $view = 'filament.app.pages.sampling';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Sampling';

    protected static ?string $title = '';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
