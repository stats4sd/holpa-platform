<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class AddData extends Page
{
    protected static string $view = 'filament.app.pages.add-data';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Additional Data';

    protected static ?string $title = '';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
