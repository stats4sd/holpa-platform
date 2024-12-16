<?php

namespace App\Filament\App\Clusters\Localisations\Pages;

use App\Filament\App\Clusters\Localisations;
use Filament\Pages\Page;

class LocalisationIntro extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.clusters.localisations.pages.localisation-intro';

    protected static ?string $title = 'Localisation - Guide';

    protected static ?string $cluster = Localisations::class;
}
