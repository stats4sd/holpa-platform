<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Team;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Contracts\HasForms;
use App\Models\XlsformTemplateLanguage;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use App\Exports\XlsformTemplateLanguageExport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TeamLocalesTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Team $team;


    public function mount()
    {
        $this->team = auth()->user()->latestTeam;
    }

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn (): BelongsToMany => $this->team->locales())
            ->inverseRelationship('teams')
            ->columns([
                TextColumn::make('languageLabel')->label('Language')
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('remove')
                    ->label('Remove')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->action(function ($record, $livewire) {
                        $locale = $record->locale_id;
                        $team = auth()->user()->latestTeam->id;
                        $team = \App\Models\Team::find($team);

                        $team->locales()->detach($locale);

                        Notification::make()
                            ->title('Success')
                            ->body('Translation successfully removed')
                            ->success()
                            ->send();
                    }),
            ])
            ->paginated(false)
            ->emptyStateHeading('No translations selected');
    }

    public function render(): View
    {
        return view('livewire.team-locales-table');
    }
}
