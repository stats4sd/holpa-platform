<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\XlsformLanguages\Language;
use App\Models\XlsformLanguages\Locale;
use App\Models\Xlsforms\Xlsform;
use App\Models\Xlsforms\XlsformModule;
use App\Models\Xlsforms\XlsformModuleVersion;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class TeamTranslationEntry extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public Team $team;
    public Language $language;
    public ?Locale $selectedLocale = null;

    public bool $expanded;

    public function render()
    {
        // temp
        if ($this->language->iso_alpha2 === 'pt') {
            $this->expanded = true;
        }

        $this->selectedLocale = $this->team->locales()->wherePivot('language_id', $this->language->id)->first();

        return view('livewire.team-translation-entry');
    }

    public function table(Table $table): Table
    {
        $status = $this
            ->team
            ->xlsforms
            ->map(fn(Xlsform $xlsform) => $xlsform
                ->xlsformModules
                ->map(fn(XlsformModule $xlsformModule) => $xlsformModule
                    ->xlsformModuleVersions
                    ->map(fn(XlsformModuleVersion $xlsformModuleVersion) => $xlsformModuleVersion
                        ->locales
                    )));


        return $table
            ->relationship(fn() => $this->language
                ->locales()
            )
            ->columns([
                TextColumn::make('language_label')->label('Translation'),
                TextColumn::make('status')->label('Status'),
            ])
            ->paginated(false)
            ->emptyStateHeading("No translations available.")
            ->heading('Available Translations')
            ->headerActions([
                Action::make('Add new Translation')
                    ->form([
                        TextInput::make('description')->label('Enter a label for the translation')
                            ->helperText('E.g. "Portuguese (Brazil)"'),
                    ])
                    ->action(function (array $data) {
                        $this->language->locales()->create([
                            'description' => $data['description'],
                        ]);
                    }),
            ])
            ->actions([
                Action::make('Review Survey Text')
                    ->modalContent(fn(Locale $record) => view('team-translation-review', ['locale' => $record, 'team' => $this->team]))
                    ->modalCancelAction(fn(StaticAction $action) => $action->extraAttributes(['class' => 'buttonb']))
                    ->modalSubmitAction(fn(StaticAction $action) => $action->extraAttributes(['class' => 'buttona']))
                    ->modalFooterActionsAlignment(Alignment::End)
                    ->form(
                        [
                            Section::make('Upload Completed Translation Files')
                                ->schema([
                                    FileUpload::make('household_survey_translation'),
                                    FileUpload::make('fieldwork_survey_translation'),
                                ])
                                ->columns(2),
                        ])
                    ->action(fn(array $data) => dd($data)),
                Action::make('Select')
                    ->visible(fn(Locale $record) => $this->selectedLocale?->id !== $record->id)
                    ->tooltip('Select this translation for your survey')
                    ->action(function (Locale $record) {
                        $record->language->teams()->updateExistingPivot($this->team->id, ['locale_id' => $record->id]);
                    }),
            ]);
    }

}
