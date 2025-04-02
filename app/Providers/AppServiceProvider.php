<?php

namespace App\Providers;

use App\Filament\App\Pages\DataAnalysis;
use App\Filament\App\Pages\DataCollection;
use App\Filament\App\Pages\Lisp;
use App\Filament\App\Pages\LispIndicators;
use App\Filament\App\Pages\LispWorkshop;
use App\Filament\App\Pages\MoreInstructions;
use App\Filament\App\Pages\Pilot;
use App\Filament\App\Pages\PlaceAdaptations;
use App\Filament\App\Pages\Sampling;
use App\Filament\App\Pages\SurveyDashboard;
use App\Filament\App\Pages\SurveyLanguages\SurveyTranslations;
use App\Filament\App\Pages\TeamOdkView;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
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

        // Enable migrations in subfolders
        $migrationsPath = database_path('migrations');
        $directories = glob($migrationsPath.'/*', GLOB_ONLYDIR);
        $paths = array_merge([$migrationsPath], $directories);

        $this->loadMigrationsFrom($paths);

    }

    public function registerPageSpecificHooks(): void
    {

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes):
            View => view('filament.app.pages.info-panels.survey-dashboard', ['scopes' => $scopes]),
            scopes: SurveyDashboard::class,
        );

    }
}
