<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;

// use App\Models\Program;

use Stats4sd\FilamentTeamManagement\Models\Program;
use Stats4sd\FilamentTeamManagement\Filament\App\Pages\RegisterProgram;
use Stats4sd\FilamentTeamManagement\Http\Middleware\CheckIfProgramAdmin;
use Stats4sd\FilamentTeamManagement\Http\Middleware\SetLatestProgramMiddleware;

use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationItem;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class ProgramPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('program')
            ->path('program')
            ->tenant(Program::class)
            // disable "Register New Program" option in multi-tenancy
            // new program should be created by admin, user should not be able to create a new program
            ->tenantRegistration(RegisterProgram::class)
            ->tenantMiddleware([
                SetLatestProgramMiddleware::class,
            ])
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            // to include "My Program" filament resource from submodule
            ->discoverResources(in: app_path('../packages/filament-team-management/src/Filament/Program/Resources'), for: 'Stats4sd\\FilamentTeamManagement\\Filament\\Program\\Resources')
            ->discoverPages(in: app_path('Filament/Program/Pages'), for: 'App\\Filament\\Program\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Program/Widgets'), for: 'App\\Filament\\Program\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                CheckIfProgramAdmin::class,
            ])
            ->navigationItems([
                NavigationItem::make()
                    ->label(__('Return to Front end'))
                    ->icon('heroicon-o-home')
                    ->url(url('/app')),
            ])
            ->darkMode(false)
            ->plugins([]);
    }
}
