<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class PlaceAdaptations extends Page
{
    protected static string $view = 'filament.app.pages.place-adaptations';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Place-based adaptations';

    protected static ?string $title = '';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
