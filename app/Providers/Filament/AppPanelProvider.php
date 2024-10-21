<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;

use Stats4sd\FilamentTeamManagement\Models\Team;
use Stats4sd\FilamentTeamManagement\Filament\App\Pages\RegisterTeam;
use Stats4sd\FilamentTeamManagement\Http\Middleware\SetLatestTeamMiddleware;

use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BetterFuturesStudio\FilamentLocalLogins\LocalLogins;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->tenant(Team::class)
            // disable "Register New Team" option in multi-tenancy
            // new team should be created by admin, user should not be able to create a new team
            ->tenantRegistration(RegisterTeam::class)
            ->tenantMiddleware([
                SetLatestTeamMiddleware::class,
            ])
            ->login()
            ->passwordReset()
            ->profile() // TODO: Implement more full-featured profile page
            ->colors([
                'primary' => Color::Amber,
            ])
            // to include "My Team" filament resource from submodule
            ->discoverResources(in: app_path('../packages/filament-team-management/src/Filament/App/Resources'), for: 'Stats4sd\\FilamentTeamManagement\\Filament\\App\\Resources')
            // to include ODk Form Management filament page
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            // to include role register, program register, register filament pages
            ->discoverPages(in: app_path('../packages/filament-team-management/src/Filament/App/Pages'), for: 'Stats4sd\\FilamentTeamManagement\\Filament\\App\\Pages')
            ->pages([
                // To show dashbaord in sidebar, we need to comment custom navigation() in bottom part
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                // It is useful to check filament version in filament info widget in dashboard
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
            ])
            ->navigationItems([
                NavigationItem::make()
                    ->label(__('Admin Panel'))
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->url(url('admin'))
                    ->visible(fn() => auth()->user()->can('access admin panel')),
                NavigationItem::make()
                    ->label(__('Program Admin Panel'))
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->url(url('program'))
                    ->visible(fn() => auth()->user()->can('access program admin panel')),
            ])
            ->darkMode(false)
            ->plugins([
                new LocalLogins(),
            ]);
    }
}
