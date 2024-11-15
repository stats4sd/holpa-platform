<?php

namespace App\Livewire;

use App\Models\Team;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
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
                TextColumn::make('language.name')->label('Language')
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('download_translation')
                    ->label('View')
                    ->button()
                    ->color('success')
                    ->visible(fn($record) => $record->status !== 'Ready for use'),
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
            ->emptyStateHeading('No translations yet');
    }

    public function render(): View
    {
        return view('livewire.team-locales-table');
    }
}
