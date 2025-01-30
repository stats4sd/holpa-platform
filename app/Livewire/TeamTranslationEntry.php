<?php

namespace App\Livewire;

use App\Imports\XlsformTemplateLanguageImport;
use App\Models\Team;
use App\Models\Xlsforms\Xlsform;
use App\Models\Xlsforms\XlsformTemplate;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Stats4sd\FilamentOdkLink\Exports\XlsformTemplateTranslationsExport;
use Stats4sd\FilamentOdkLink\Models\OdkLink\ChoiceListEntry;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Language;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;

class TeamTranslationEntry extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public Team $team;
    public Language $language;
    public ?Locale $selectedLocale = null;

    public bool $expanded;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View|null
    {
        $this->selectedLocale = $this->team->locales()->wherePivot('language_id', $this->language->id)->first();

        return view('livewire.team-translation-entry');
    }

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn() => $this->language
                ->locales()
            )
            ->recordClasses(fn(Locale $record) => $record->id === $this->selectedLocale?->id ? 'success-row' : '')
            ->columns([
                TextColumn::make('language_label')->label('Available Translations'),
                TextColumn::make('status')->label('Status'),
            ])
            ->paginated(false)
            ->emptyStateHeading("No translations available.")
            ->heading('')
            ->headerActions([
                Action::make('Add New')
                    ->extraAttributes(['class' => 'buttonb'])
                    ->icon('heroicon-o-plus-circle')
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
                Action::make('Select')
                    ->icon(fn(Locale $record) => $record->id === $this->selectedLocale?->id ? 'heroicon-o-check-circle' : '')
                    ->color(fn(Locale $record) => $record->id === $this->selectedLocale?->id ? 'success' : 'primary')
                    ->label(fn(Locale $record) => $record->id === $this->selectedLocale?->id ? 'Selected' : 'Select')
                    ->disabled(fn(Locale $record) => $this->selectedLocale?->id === $record->id)
                    ->tooltip('Select this translation for your survey')
                    ->action(function (Locale $record) {
                        $record->language->teams()->updateExistingPivot($this->team->id, ['locale_id' => $record->id]);
                    }),

                Action::make('view-edit')
                    ->label('View / Edit Translation')
                    ->modalHeading(fn(Locale $record) => 'View / Edit Translation for ' . $record->language_label)
                    ->modalContent(fn(Locale $record) => view('team-translation-review', ['locale' => $record, 'team' => $this->team]))
                    ->modalCancelAction(fn(StaticAction $action) => $action->extraAttributes(['class' => 'buttonb']))
                    ->extraModalFooterActions(fn(Locale $record, Action $action) => [
                        Action::make('edit')->visible($record->is_editable && $record->status === 'Ready for use'),
                        Action::make('duplicate')
                            ->action(function (Locale $record) use ($action) {
                                $newRecord = $record->replicate();
                                $newRecord->is_default = false;
                                $newRecord->createdBy()->associate($this->team);
                                $newRecord->save();
                            })
                            ->modalHeading(fn(Locale $record) => 'Duplicate Translation for ' . $record->language_label)
                            ->requiresConfirmation()
                            ->cancelParentActions(),
                        Action::make('submit')
                            ->extraAttributes(['class' => 'buttona'])
                            ->visible(fn(Locale $record) => $record->is_editing),

                    ])
                    ->modalFooterActionsAlignment(Alignment::End)
                    ->form(fn(\Filament\Forms\Form $form, Locale $record): \Filament\Forms\Form => $form
                        ->columns(2)
                        ->schema(fn(): array => $this->team->xlsforms->map(fn(Xlsform $xlsform) => $xlsform->xlsformTemplate)
                            ->map(fn(XlsformTemplate $xlsformTemplate) => Section::make($xlsformTemplate->title)
                                ->schema([
                                    Actions::make([

                                        // download existing translations if they exist
                                        Actions\Action::make('download_' . $xlsformTemplate->id)
                                            ->link()
                                            ->label("Download existing translations")
                                            ->action(fn(Locale $record) => Excel::download(new XlsformTemplateTranslationsExport($xlsformTemplate, $this->selectedLocale), "{$xlsformTemplate->title} translation - {$record->language_label}.xlsx")),

                                        // download blank template if needed
                                        Actions\Action::make('download_' . $xlsformTemplate->id)
                                            ->link()
                                            ->visible(fn(Locale $record) => $record->is_editable && $record->status !== 'Ready for use')
                                            ->label("Download empty translation template")
                                            ->action(fn(Locale $record) => Excel::download(new XlsformTemplateTranslationsExport($xlsformTemplate, $this->selectedLocale, empty: true), "{$xlsformTemplate->title} translation - {$record->language_label}.xlsx")),
                                    ]),
                                    FileUpload::make('upload_' . $xlsformTemplate->id)
                                        ->visible(fn(Locale $record) => $record->is_editing)
                                        ->label("Upload completed {$xlsformTemplate->title} translation file")
                                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                                        ->maxSize(10240)
                                        ->rules([
                                            fn(Get $get, Locale $record) => $this->validateFileUpload($get('upload_' . $xlsformTemplate->id), $record, $xlsformTemplate),
                                        ]),
                                ])
                                ->columnSpan(1),
                            )->toArray(),
                        )
                    )
                    ->action(function (array $data) {
                        // upload the files
                        if ($data['household_survey_translation']) {
                            ray('hi');
                            Excel::import(
                                new XlsformTemplateLanguageImport($this->team->xlsforms->first()->xlsformTemplate, $this->selectedLocale),
                                Storage::path($data['household_survey_translation'])
                            );

                        }
                    }),

            ]);
    }

    public function validateFileUpload(array $upload, Locale $record, XlsformTemplate $xlsformTemplate): \Closure
    {
        return function (string $attribute, string $value, \Closure $fail) use ($upload, $record, $xlsformTemplate) {

            $file = collect($upload)->first();

            /** @var Collection $rows */
            $rows = Excel::toCollection((object)[], $file)[0];

            $headers = $rows->shift();

            // check the required columns exist
            $rowTypeIndex = $headers->contains('type');
            $nameIndex = $headers->contains('name');
            $stringTypeIndex = $headers->contains('translation type');
            $currentTranslationIndex = $headers->contains($record->language_label);

            if ($currentTranslationIndex === false || $nameIndex === false || $stringTypeIndex === false) {

                $missingColumns = collect([
                    $nameIndex ? '' : 'name',
                    $stringTypeIndex ? '' : 'translation type',
                    $currentTranslationIndex ? '' : $record->language_label,
                ]);

                return $fail("The uploaded file is missing the following required columns: {$missingColumns->join(', ')}. Please check that you are using the template downloaded from this platform, and please do not edit the column headers.");
            }

            // check that all the required names are present
            $processor = new XlsformTemplateTranslationsExport($xlsformTemplate, $record);

            $templateSurveyRows = $xlsformTemplate->surveyRows
                ->map(fn(SurveyRow $entry) => $processor->processEntry($entry));
            $templateChoiceListEntries = $xlsformTemplate->choiceListEntries
                ->map(fn(ChoiceListEntry $entry) => $processor->processEntry($entry));

            // make sure all template survey rows are present in the $rows collection
            $missingSurveyRows = $templateSurveyRows
                ->filter(fn($entry) => $rows->doesntcontain($entry, '=', 'name'))
                ->filter(fn($entry) => $rows->doesntcontain($entry, '=', 'translation_type'));

            // make sure all template choice list rows are present in the $rows collection
            $missingChoiceListEntries = $templateChoiceListEntries
                ->filter(fn($entry) => $rows->doesntcontain($entry, '=', 'name'))
                ->filter(fn($entry) => $rows->doesntcontain($entry, '=', 'translation_type'));

            // TODO: finish validating that all required rows are present;
            // TODO: validate that all translation_types are valid language string types.

            return true;

        };
    }

}
