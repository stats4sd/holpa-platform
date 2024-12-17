<?php

namespace App\Providers;

use App\Filament\App\Clusters\Localisations\Resources\ChoiceListEntryResource\Pages\ListChoiceListEntries;
use App\Filament\App\Pages\DietDiversity;
use App\Filament\App\Pages\TimeFrame;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // unguard all models at once, so that filament-odk-link package XlsformTemplate model can be created successfully
        Model::unguard();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        $this->registerPageSpecificHooks();
    }

    public function registerPageSpecificHooks(): void
    {

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(): Factory|\Illuminate\Contracts\View\View|Application|\Illuminate\View\View => view('filament.app.components.place-locations-top-menu'),
            scopes: [
                ListChoiceListEntries::class,
                TimeFrame::class,
                DietDiversity::class,
                ]
        );
    }
}
