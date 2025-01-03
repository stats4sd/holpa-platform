<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\XlsformLanguages\Language;
use App\Models\XlsformLanguages\Locale;
use App\Models\Xlsforms\XlsformTemplate;
use Filament\Forms\Components\Button;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class LocalesTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function table(Table $table): Table
    {
        return $table
            ->query(Locale::query()
                // Exclude records already selected by the team
                ->whereDoesntHave('teams', function ($query) {
                    $query->where('locale_team.team_id', auth()->user()->latestTeam->id);
                })
            )
            ->columns([
                TextColumn::make('languageLabel')
                    ->label('Language'),
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
                    }),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('viewOrUpdate')
                    ->label(fn($record) => $record->status === 'Ready for use' ? 'View' : 'Update')
                    ->color(fn($record) => $record->status === 'Ready for use' ? 'blue' : 'warning')
                    ->button()
                    ->modalHeading(fn($record) => $record->status === 'Ready for use' ? 'VIEW TRANSLATIONS' : 'UPDATE TRANSLATIONS')
                    ->modalSubheading(fn($record) => $record->status === 'Ready for use'
                        ? $record->languageLabel . ' is ready for use'
                        : $record->languageLabel . ' requires updates before it can be used')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalContent(fn($record) => view('filament.app.locale-modal', ['locale_id' => $record->id])),
                \Filament\Tables\Actions\Action::make('select')
                    ->label('Select')
                    ->color('success')
                    ->button()
                    ->visible(fn($record) => $record->status === 'Ready for use')
                    ->action(function ($record, $livewire) {
                        $team = auth()->user()->latestTeam->id;
                        $team = Team::find($team);
                        $team->locales()->attach($record->id);

                        $this->dispatch('refreshTable')->to(TeamLocalesTable::class);

                        Notification::make()
                            ->title('Success')
                            ->body('Translation \'' . $record->languageLabel . '\' successfully selected')
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('No translations available for selection')
            ->emptyStateDescription('')
            ->paginated(false)
            ->headerActions([
                \Filament\Tables\Actions\Action::make('create')
                    ->label('ADD NEW TRANSLATION')
                    ->color('orange')
                    ->modalButton('Add language')
                    ->modalCancelAction(false)
                    ->modalWidth(MaxWidth::SixExtraLarge)
                    ->form([
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('info')
                                    ->label('')
                                    ->content(new HtmlString('
                                        <b>To create a new translation:
                                            <ol>
                                                <li>1. Add it to the list of translations by selecting a language and optionally,
                                                    adding a description e.g., to specify if the translation is for a particular dialect or region.
                                                </li>
                                                <li>2. Find the language in the list and download the translation file.
                                                </li>
                                                <li>3. In the translation file, fill in the indicated fields with the translation in the destination
                                                    language.
                                                </li>
                                                <li>4. Find the language in the list and upload the completed translation file.
                                                </li>
                                            </ol>
                                        </b>
                                        <p class="pt-4">
                                            You may wish to use software such as DeepL to speed up the translation process
                                             but it must be checked by a person who is fluent in the language and
                                             conversant with the topics covered before submission to the HOLPA platform.
                                        </p>
                                        <p class="pt-4">
                                            Translations of the HOLPA survey which are uploaded to the platform are made
                                             available to other teams. By uploading a translation here, you are agreeing
                                             to have it be shared on the platform and used by other teams.
                                        </p>
                                    '))
                                    ->columnSpan(1),
                                Group::make()
                                    ->schema([
                                        Select::make('language_id')
                                            ->label('Language')
                                            ->required()
                                            ->disabledOn('edit')
                                            ->options(function () {
                                                return Language::all()
                                                    ->mapWithKeys(function ($language) {
                                                        return [$language->id => $language->name . ' (' . $language->iso_alpha2 . ')'];
                                                    })
                                                    ->toArray();
                                            }),
                                        TextInput::make('description')
                                            ->placeholder('Optional description')
                                            ->maxLength(255),
                                    ])
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->after(function (array $data): array {
                        $locale = Locale::create([
                            'language_id' => $data['language_id'],
                            'description' => $data['description'] ?? null,
                        ]);

                        $xlsformTemplates = XlsformTemplate::all();

                        foreach ($xlsformTemplates as $template) {
                            $template->xlsformTemplateLanguages()->create([
                                'language_id' => $data['language_id'],
                                'locale_id' => $locale->id,
                            ]);
                        }

                        return $data;
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.xlsform-languages-table');
    }
}
