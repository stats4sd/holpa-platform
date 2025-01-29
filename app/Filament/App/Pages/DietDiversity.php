<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use App\Models\Xlsforms\XlsformModuleVersion;
use App\Services\HelperService;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class DietDiversity extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public ?array $data = [];
    public Team $team;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.diet-diversity';


    public function mount(): void
    {
        $this->team = HelperService::getSelectedTeam();
        $this->form->fill($this->team->toArray());
    }

    public function form(Form $form): Form
    {
        // If the team's `diet_diversity_module_version_id` is null, set the default using the team's country
        if (is_null($this->team->diet_diversity_module_version_id)) {
            $this->team->diet_diversity_module_version_id = XlsformModuleVersion::query()
                ->whereHas('xlsformModule', fn($query) => $query->where('name', 'diet_quality'))
                ->where('country_id', $this->team->country_id)
                ->value('id');

            $this->team->save();
        }

        return $form
            ->statePath('data')
            ->model($this->team)
            ->schema([
                Select::make('diet_diversity_module_version_id')
                    ->live()
                    ->relationship('dietDiversityModuleVersion', 'name', fn($query) => $query
                        ->where('is_default', 0)
                        ->whereHas('xlsformModule', fn($query) => $query->where('name', 'diet_quality'))
                    )
                    ->afterStateUpdated(fn(self $livewire) => $livewire->saveData()),
            ]);
    }

    public function saveData(): void
    {

        $this->team->update($this->form->getState());

        $this->resetTable();

        Notification::make('updated')
            ->success()
            ->title('Diet Diversity Module Selected Successfully')
            ->send();
    }

    public function table(Table $table): Table
    {
        if ($this->team->diet_diversity_module_version_id) {
            $moduleVersion = $this->team->dietDiversityModuleVersion;
        } else {
            //  default to the 'default' diet diversity module version;
            $moduleVersion = XlsformModuleVersion::where('is_default', 1)
                ->whereHas('xlsformModule', fn($query) => $query->where('name', 'diet_quality'))
                ->first();
        }

        return $table
            ->query(fn() => $moduleVersion->surveyRows()
            )
            ->columns([
                TextColumn::make('type')->label('Question Type'),
                TextColumn::make('name')->label('Name'),
                TextColumn::make('default_label')->label('Label (en)')->wrap(),
                TextColumn::make('default_hint')->label('Hint (en)')->wrap(),
            ]);
    }
}
