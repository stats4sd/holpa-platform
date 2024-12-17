<?php

namespace App\Providers;

use App\Filament\App\Pages\AddData;
use App\Filament\App\Pages\SurveyDashboard;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

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

        $this->renderAppPanelHooks();
    }

    public function renderAppPanelHooks()
    {
        // get all pages in specific namespace
        foreach (glob(app_path('Filament/App/Filament/Pages/*.php')) as $file) {
            $page = str_replace(app_path('Filament/App/Pages/'), '', $file);
            $page = str_replace('.php', '', $page);
            $page = str_replace('/', '\\', $page);

            dump($page);
        }

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.survey-dashboard', ['scopes' => $scopes]),
            scopes: SurveyDashboard::class,
        );


//DataAnalysis.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.data-analysis', ['scopes' => $scopes]),
            scopes: AddData::class,
        );
//DataCollection.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.data-collection', ['scopes' => $scopes]),
            scopes: AddData::class,
        );
//LISP.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.lisp', ['scopes' => $scopes]),
            scopes: AddData::class,
        );
//LispIndicators.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.lisp-indicators', ['scopes' => $scopes]),
            scopes: AddData::class,
        );
//LispWorkshop.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.lisp-workshop', ['scopes' => $scopes]),
            scopes: AddData::class,
        );
//MoreInstructions.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.more-instructions', ['scopes' => $scopes]),
            scopes: AddData::class,
        );
//Pilot.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.pilot', ['scopes' => $scopes]),
            scopes: AddData::class,
        );
//PlaceAdaptations.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.place-adaptations', ['scopes' => $scopes]),
            scopes: AddData::class,
        );
//Sampling.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.sampling', ['scopes' => $scopes]),
            scopes: AddData::class,
        );
//SurveyLanguages.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.survey-languages', ['scopes' => $scopes]),
            scopes: AddData::class,
        );
//TeamOdkView.php
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn(array $scopes): View => view('filament.app.pages.info-panels.team-odk-view', ['scopes' => $scopes]),
            scopes: AddData::class,
        );

    }
}
