<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use App\Models\XlsformTemplateLanguage;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class XlsformLanguagesTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(XlsformTemplateLanguage::query()
                // Exclude records already associated with the team
                ->whereDoesntHave('locale.teams', function ($query) {
                    $query->where('locale_team.team_id', auth()->user()->latestTeam->id);
                })
            )
            ->columns([
                TextColumn::make('xlsformTemplate.title')
                    ->label('ODK Form')
                    ->sortable(),
                TextColumn::make('languageLabel')
                    ->label('Language')
                    ->sortable(),
                TextColumn::make('status')
                    ->icon(fn(string $state): string => match ($state) {
                        'Ready for use' => 'heroicon-o-check-circle',
                        'Not added' => 'heroicon-o-exclamation-circle',
                        'Out of date' => 'heroicon-o-exclamation-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Ready for use' => 'success',
                        'Not added' => 'danger',
                        'Out of date' => 'danger',
                    })
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('download_translation')
                    ->label('Download translation file')
                    ->button()
                    ->visible(fn($record) => $record->status !== 'Ready for use'),
                \Filament\Tables\Actions\Action::make('upload_translation')
                    ->label('Upload translation file')
                    ->button()
                    ->visible(fn($record) => $record->status !== 'Ready for use')
                    ->icon('heroicon-m-arrow-up-circle')
                    ->modalHeading(function (XlsformTemplateLanguage $record) {                
                        return 'Upload Completed Translation File for ' . $record->languageLabel;
                    }),                
                \Filament\Tables\Actions\Action::make('select')
                    ->label('Select')
                    ->color('success')
                    ->button()
                    ->visible(fn($record) => $record->status === 'Ready for use')
                    ->action(function ($record, $livewire) {
                        $locale = $record->locale_id;
                        $team = auth()->user()->latestTeam->id;
                        $team = \App\Models\Team::find($team);

                        $team->locales()->attach($locale);

                        Notification::make()
                            ->title('Success')
                            ->body('Translation successfully selected')
                            ->success()
                            ->send();
                    })
            ])
            ->emptyStateHeading('No translations yet')
            ->paginated(false)
            ->headerActions([
                \Filament\Tables\Actions\Action::make('create')
                    ->label('Add new translation')
            ]);
    }

    public function render()
    {
        return view('livewire.xlsform-languages-table');
    }
}
