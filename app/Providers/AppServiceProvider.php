<?php

namespace App\Providers;

use App\Filament\App\Clusters\Localisations\Resources\ChoiceListEntryResource\Pages\ListChoiceListEntries;
use App\Filament\App\Pages\AddData;
use App\Filament\App\Pages\DataAnalysis;
use App\Filament\App\Pages\DataCollection;
use App\Filament\App\Pages\DietDiversity;
use App\Filament\App\Pages\Lisp;
use App\Filament\App\Pages\LispIndicators;
use App\Filament\App\Pages\LispWorkshop;
use App\Filament\App\Pages\MoreInstructions;
use App\Filament\App\Pages\Pilot;
use App\Filament\App\Pages\PlaceAdaptations;
use App\Filament\App\Pages\Sampling;
use App\Filament\App\Pages\SurveyDashboard;
use App\Filament\App\Pages\SurveyTranslations;
use App\Filament\App\Pages\TeamOdkView;
use App\Filament\App\Pages\TimeFrame;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
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

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.data-analysis', ['scopes' => $scopes]),
            scopes: DataAnalysis::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.data-collection', ['scopes' => $scopes]),
            scopes: DataCollection::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.lisp', ['scopes' => $scopes]),
            scopes: Lisp::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.lisp-indicators', ['scopes' => $scopes]),
            scopes: LispIndicators::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.lisp-workshop', ['scopes' => $scopes]),
            scopes: LispWorkshop::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.more-instructions', ['scopes' => $scopes]),
            scopes: MoreInstructions::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.pilot', ['scopes' => $scopes]),
            scopes: Pilot::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.place-adaptations', ['scopes' => $scopes]),
            scopes: PlaceAdaptations::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.sampling', ['scopes' => $scopes]),
            scopes: Sampling::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.survey-languages', ['scopes' => $scopes]),
            scopes: SurveyTranslations::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.team-odk-view', ['scopes' => $scopes]),
            scopes: TeamOdkView::class,
        );

    }
}
