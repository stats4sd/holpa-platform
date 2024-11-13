<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class DataCollection extends Page
{
    protected static string $view = 'filament.app.pages.data-collection';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Data Collection';

    protected static ?string $title = '';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
