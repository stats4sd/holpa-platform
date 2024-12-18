<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use App\Models\XlsformModuleVersion;
use App\Models\XlsformTemplates\SurveyRow;
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

class TimeFrame extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.app.pages.time-frame';

    public ?array $data = [];
    public Team $team;

    public function mount(): void
    {
        $this->team = HelperService::getSelectedTeam();
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
                    ->afterStateUpdated(fn(self $livewire) => $livewire->saveData()),
            ]);
    }

    public function saveData()
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
            ->query(fn() => SurveyRow::query()
                ->whereHasMorph('template', [XlsformModuleVersion::class], fn($query) => $query->where('is_default', 1))
                ->whereHas('languageStrings', fn($query) => $query->whereLike('text', '%${time_frame}%'))
            )
            ->columns([
                TextColumn::make('name')->label('Name'),
                TextColumn::make('default_label')->label('Label (en)')->wrap(),
                TextColumn::make('default_hint')->label('Hint (en)')->wrap(),
            ]);
    }
}
