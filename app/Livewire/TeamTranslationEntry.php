<?php

namespace App\Livewire;

use App\Exports\XlsformTemplateTranslationsExport;
use App\Models\Team;
use App\Models\XlsformLanguages\Language;
use App\Models\XlsformLanguages\Locale;
use App\Models\Xlsforms\SurveyRow;
use App\Models\Xlsforms\Xlsform;
use App\Models\Xlsforms\XlsformModule;
use App\Models\Xlsforms\XlsformModuleVersion;
use App\Models\Xlsforms\XlsformTemplate;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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
                                    FileUpload::make('household_survey_translation')
                                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                                        ->maxSize(10240)
                                        ->rules([
                                            fn(Get $get, Locale $record) => $this->validateFileUpload($get('household_survey_translation'), $record, $this->team->xlsforms->first()->xlsformTemplate),
                                        ]),
                                    FileUpload::make('fieldwork_survey_translation')
                                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                                        ->maxSize(10240)
                                        ->rules([
                                            fn(Get $get, Locale $record) => $this->validateFileUpload($get('fieldwork_survey_translation'), $record, $this->team->xlsforms->last()->xlsformTemplate),
                                        ]),
                                ])
                                ->columns(2),
                        ])
                    ->action(function (array $data) {
                        // upload the files
                        if ($data['household_survey_translation']) {

                            Excel::import(
                                new XlsformTemplateTranslationsExport($this->team->xlsforms->first()->xlsformTemplate, $this->selectedLocale),
                                Storage::path($data['household_survey_translation'])
                            );

                        }
                    }),
                Action::make('Select')
                    ->visible(fn(Locale $record) => $this->selectedLocale?->id !== $record->id)
                    ->tooltip('Select this translation for your survey')
                    ->action(function (Locale $record) {
                        $record->language->teams()->updateExistingPivot($this->team->id, ['locale_id' => $record->id]);
                    }),
            ]);
    }

    public function validateFileUpload(array $upload, Locale $record, XlsformTemplate $xlsformTemplate): \Closure
    {
        return function (string $attribute, string $value, \Closure $fail) use ($upload, $record, $xlsformTemplate) {

            $file = collect($upload)->first();

            /** @var Collection $rows */
            $rows = Excel::toCollection([], $file)[0];

            $headers = $rows->shift();

            // check the required columns exist
            $nameIndex = array_search('name', $headers);
            $stringTypeIndex = array_search('translation_type', $headers);
            $currentTranslationIndex = array_search($record->language_label, $headers);

            if ($currentTranslationIndex === false || $nameIndex === false || $stringTypeIndex === false) {

                $missingColumns = collect([
                    $nameIndex ? '' : 'name',
                    $stringTypeIndex ? '' : 'translation_type',
                    $currentTranslationIndex ? '' : $record->language_label,
                ]);

                return $fail("The uploaded file is missing the following required columns: {$missingColumns->join(', ')}. Please check that you are using the template downloaded from this platform, and please do not edit the column headers.");
            }

            // check that all the required names are present
            $processor = new XlsformTemplateTranslationsExport($xlsformTemplate, $record);

            $templateSurveyRows = $xlsformTemplate->surveyRows
                ->map(fn(SurveyRow $entry) => $processor->processEntry($entry));
            $templateChoiceListEntries = $xlsformTemplate->choiceListEntries
                ->map(fn(SurveyRow $entry) => $processor->processEntry($entry));

            // make sure all template survey rows are present in the $rows collection
            $missingSurveyRows = $templateSurveyRows
                ->filter(fn($entry) => $rows->doesntcontain($entry, '=', 'name'))
                ->filter(fn($entry) => $rows->doesntcontain($entry, '=', 'translation_type'));


            return true;

        };
    }

}
