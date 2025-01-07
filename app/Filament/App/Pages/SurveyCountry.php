<?php

namespace App\Filament\App\Pages;

use App\Services\HelperService;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class SurveyCountry extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.app.pages.survey-country';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Survey Country & Languages';

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            SurveyLanguages::getUrl() => 'Survey Languages',
            static::getUrl() => static::getTitle(),
        ];
    }

    public function getMaxContentWidth(): MaxWidth|string|null
    {
        return MaxWidth::Full;
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('formData')
            ->model(HelperService::getSelectedTeam())
            ->schema([
            Select::make('country_id')
                ->searchable()
                ->relationship('country', 'name')
                ->afterStateUpdated(fn(self $livewire) => $livewire->saveCountry())
        ]);
    }

    public function saveCountry()
    {
        $this->form->saveRelationships();
    }
}
