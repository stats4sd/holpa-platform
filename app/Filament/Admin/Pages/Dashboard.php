<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\SummaryWidget;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string $view = 'filament.admin.pages.dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function canAccess(): bool
    {
        return auth()->user()->can('do admin');
    }

    public function getHeaderWidgets(): array
    {
        return [
            SummaryWidget::class,
        ];
    }

    protected ?string $heading = '';
}
