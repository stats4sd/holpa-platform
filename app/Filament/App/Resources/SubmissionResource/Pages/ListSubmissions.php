<?php

namespace App\Filament\App\Resources\SubmissionResource\Pages;

use App\Filament\App\Pages\InitialPilot;
use App\Filament\App\Pages\PlaceAdaptations;
use App\Filament\App\Resources\SubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubmissions extends ListRecords
{
    protected static string $resource = SubmissionResource::class;

    protected ?string $heading = "Test Submissions";

    protected static string $view = 'filament.app.resources.submission-resource.pages.view-submission';

        public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\App\Pages\SurveyDashboard::getUrl() => 'Survey Dashboard',
            PlaceAdaptations::getUrl() => 'Place Adaptations',
            InitialPilot::getUrl() => 'Initial Pilot',
            static::getUrl() => static::getTitle(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
