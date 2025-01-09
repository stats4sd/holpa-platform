<?php

namespace App\Livewire;

use App\Exports\XlsformTemplateTranslationsExport;
use App\Models\Team;
use App\Models\XlsformLanguages\Locale;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class TeamTranslationReview extends Component implements HasActions, HasForms
{
    use InteractsWithForms;
    use InteractsWithActions;

    public Team $team;
    public Locale $locale;


    public function render()
    {
        return view('livewire.team-translation-review');
    }

    public function downloadHouseholdAction(): Action
    {
        return Action::make('downloadHousehold')
            ->label('Download Household Survey Translation File')
            ->action(function () {

                $xlsformTemplate = $this->team->xlsforms->first()->xlsformTemplate; // Hardcoded assumption for now. TODO: Update!

                return Excel::download(new XlsformTemplateTranslationsExport($xlsformTemplate, $this->locale), 'HOLPA_household_translations - ' . $this->locale->language_label . '.xlsx');

            });
    }

    public function downloadFieldworkAction(): Action
    {
        return Action::make('downloadFieldwork')
            ->label('Download Fieldwork Survey Translation File')
            ->action(function () {

                $xlsformTemplate = $this->team->xlsforms->last()->xlsformTemplate; // Hardcoded assumption for now. TODO: Update!

                return Excel::download(new XlsformTemplateTranslationsExport($xlsformTemplate, $this->locale), 'HOLPA_household_translations - ' . $this->locale->language_label . '.xlsx');

            });
    }
}
