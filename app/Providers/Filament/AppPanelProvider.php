<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\Team;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use App\Filament\App\Pages\RegisterTeam;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Middleware\SetLatestTeamMiddleware;
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
            // Note: To make the register link working properly, we need to specify path as "app" instead of "" for app panel
            // ->path('')
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
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
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

                // TODO: why it works when there is no permission record "view the admin panel"...?
                NavigationItem::make()
                    ->label(__('Admin Panel'))
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->url(url('admin'))
                    ->visible(fn() => auth()->user()->can('view the admin panel')),

                // show Program Admin Panel button if user belongs to any program
                NavigationItem::make()
                    ->label(__('Program Admin Panel'))
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->url(url('program'))
                    ->visible(fn() => count(auth()->user()->programs) > 0),
            ])
            ->darkMode(false)
            ->plugins([
                new LocalLogins(),
            ]);
    }
}
