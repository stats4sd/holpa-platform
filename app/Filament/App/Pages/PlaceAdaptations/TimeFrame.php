<?php

namespace App\Filament\App\Pages\PlaceAdaptations;

use App\Filament\App\Pages\SurveyDashboard;
use App\Models\Team;
use App\Services\HelperService;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;

class TimeFrame extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.place-adaptations.time-frame';

    protected ?string $heading = 'Survey Testing - Adapt Time Frame';

    protected ?string $subheading = 'Specify the time frame for your survey';

    public ?array $data = [];

    public Team $team;

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            PlaceAdaptationsIndex::getUrl() => 'Place Adaptations',
            static::getUrl() => static::getTitle(),
        ];
    }

    public function mount(): void
    {
        $this->team = HelperService::getCurrentOwner();
        $this->form->fill($this->team->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->model($this->team)
            ->schema([
                TextInput::make('time_frame')
                    ->live()
                    ->afterStateUpdated(fn (self $livewire) => $livewire->saveData()),
            ]);
    }

    public function saveData(): void
    {

        $this->team->update($this->form->getState());

        Notification::make('updated')
            ->success()
            ->title('Time Frame Updated Successfully')
            ->send();
    }

    public function table(Table $table): Table
    {

        return $table
            ->query(
                fn () => SurveyRow::query()
                    ->whereHas('xlsformModuleVersion', fn ($query) => $query->where('is_default', 1))
                    ->whereHas('languageStrings', fn ($query) => $query->whereLike('text', '%${time_frame}%'))
            )
            ->columns([
                TextColumn::make('name')->label('Name'),
                TextColumn::make('default_label')->label('Label (en)')->wrap(),
                TextColumn::make('default_hint')->label('Hint (en)')->wrap(),
            ]);
    }
}
