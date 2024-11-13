<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class DataAnalysis extends Page
{
    protected static string $view = 'filament.app.pages.data-analysis';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Data Analysis';

    protected static ?string $title = '';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
