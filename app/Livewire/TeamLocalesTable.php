<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Team;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
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
                ...$this->getViewActions(),
                Action::make('remove')
                    ->label('Remove')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->action(function ($record) {    
                        $this->team->locales()->detach($record->id);
    
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

    private function getViewActions(): array
    {
        return $this->team->locales->flatMap(function ($locale) {
            return $locale->xlsformTemplateLanguages->map(function ($templateLanguage) use ($locale) {
                $template = $templateLanguage->xlsformTemplate;

                return Action::make("view-templatelanguage-{$templateLanguage->id}")
                    ->label("View {$template->title}")
                    ->color('primary')
                    ->button()
                    ->action(function () use ($template, $templateLanguage) {
                        $currentDate = Carbon::now()->format('Y-m-d');
                        $filename = "HOLPA - {$template->title} - translation - {$templateLanguage->localeLanguageLabel} - {$currentDate}.xlsx";

                        return Excel::download(new XlsformTemplateLanguageExport($template, $templateLanguage), $filename);
                    })
                    ->hidden(fn($record) => $record->id !== $locale->id);
            });
        })->toArray();
    }

    public function render(): View
    {
        return view('livewire.team-locales-table');
    }
}
