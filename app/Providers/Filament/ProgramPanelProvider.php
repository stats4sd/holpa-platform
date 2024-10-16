<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Program;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationItem;
use Filament\Http\Middleware\Authenticate;
use App\Filament\App\Pages\RegisterProgram;
use App\Http\Middleware\CheckIfProgramAdmin;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Http\Middleware\AddProgramAdminMenuItems;
use App\Http\Middleware\SetLatestProgramMiddleware;
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
            ->discoverResources(in: app_path('Filament/Program/Resources'), for: 'App\\Filament\\Program\\Resources')
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

                // TODO: add menu item via middleware
                AddProgramAdminMenuItems::class,
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

                // TODO: create a "My Program" item in sidebar, link to the selected program view page directly
                // e.g. http://holpa-platform.test/program/1/programs/1
                //
                // issue: cannot get logged in user at this point, auth()->user() returns null...
                // auth()->user()->latestProgram()->id

                // NavigationItem::make()
                //     ->label(__('My Program'))
                //     ->icon('heroicon-o-home')
                //     ->group('My group')
                //     ->url(url('/program/1/programs/1')),
            ])
            ->darkMode(false)
            ->plugins([]);
    }
}
