<?php

namespace App\Livewire;

use Filament\Forms\Get;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Services\HelperService;
use Awcodes\TableRepeater\Header;
use Filament\Tables\Actions\Action;
use App\Models\Holpa\LocalIndicator;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Awcodes\TableRepeater\Components\TableRepeater;
use Filament\Actions\Concerns\InteractsWithActions;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Models\OdkLink\ChoiceList;
use Stats4sd\FilamentOdkLink\Models\OdkLink\LanguageString;
use Stats4sd\FilamentOdkLink\Models\OdkLink\ChoiceListEntry;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Language;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\LanguageStringType;

class LocalIndicatorQuestionForm extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public LocalIndicator $localIndicator;
    public XlsformModuleVersion $xlsformModuleVersion;
    public bool $expanded = true;
    public array $data;

    public function mount(): void
    {
        $this->xlsformModuleVersion = $this->localIndicator->xlsformModuleVersion ?? $this->localIndicator->xlsformModuleVersion()->create([
            'name' => $this->localIndicator->name,
            'is_default' => false,
        ]);

        $this->localIndicator->xlsformModuleVersion()->associate($this->xlsformModuleVersion);
        $this->localIndicator->save();


        $this->xlsformModuleVersion->load(['surveyRows.languageStrings', 'surveyRows.choiceList.choiceListEntries.languageStrings']);

        $this->form->fill($this->xlsformModuleVersion->toArray());
    }


    // show questions in a table for better readability
    public function table(Table $table): Table
    {
        $locales = $this->localIndicator->team->locales;

        $choiceListHeaders = $locales->map(function (Locale $locale) {
            return [
                Header::make('label_' . $locale->id)->label('Label - ' . $locale->language_label),
            ];
        })->flatten()->toArray();

        // for new questions, set the default languageStrings to 'label' + 'hint' - one for each of the team locales.
        $defaultLanguageStringState = $locales->map(function (Locale $locale) {
            return [
                [
                    'locale_id' => $locale->id,
                    'language_string_type_id' => LanguageStringType::where('name', 'label')->first()->id,
                    'text' => '',
                ],
                [
                    'locale_id' => $locale->id,
                    'language_string_type_id' => LanguageStringType::where('name', 'hint')->first()->id,
                    'text' => '',
                ],
            ];
        })->flatten(1)->toArray();

        $defaultChoiceLanguageStringState = $locales->map(function (Locale $locale) {
            return [
                'locale_id' => $locale->id,
                'language_string_type_id' => LanguageStringType::where('name', 'label')->first()->id,
                'text' => '',
            ];
        })->toArray();


        return $table
            ->query(
                fn() => SurveyRow::query()
                    ->where('xlsform_module_version_id', $this->xlsformModuleVersion->id)
                    // sort record by row_number, to reflect the user defined ordering by drag and drop
                    ->orderBy('row_number'),
            )
            ->columns([
                TextColumn::make('type')->label('Question Type'),
                TextColumn::make('name')->label('Variable Name'),
                TextColumn::make('defaultLabel')->label('Default Label'),
            ])
            // allow user to change the ordering by drag and drop
            ->reorderable('row_number')

            // add "create new record" button in table header
            ->headerActions([
                CreateAction::make()
                    ->label('ADD QUESTION')
                    ->icon('heroicon-m-plus')
                    ->button()
                    ->color('danger')
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    ->modalHeading('ADD QUESTION')
                    ->createAnother(false)
                    ->mutateFormDataUsing(function (array $data): array {
                        // find the largest row_number of survey_rows records
                        $lastRowNumber = $this->xlsformModuleVersion->surveyRows != null ? $this->xlsformModuleVersion->surveyRows->last()->row_number : 0;

                        $data['row_number'] = $lastRowNumber + 1;

                        return $data;
                    })
                    ->form([
                        Hidden::make('xlsform_module_version_id')->default($this->xlsformModuleVersion->id)->live(),
                        Hidden::make('id'),
                        Fieldset::make('Question Information')
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                            ])
                            ->schema([
                                Select::make('type')->options([
                                    'select_one' => 'select_one',
                                    'select_multiple' => 'select_multiple',
                                    'decimal' => 'decimal',
                                    'integer' => 'integer',
                                    'text' => 'text',
                                ])->live()
                                    ->required(),
                                TextInput::make('name')->label('Variable Name')->live()->required()
                                    ->dehydrateStateUsing(fn($state): string => Str::lower(Str::slug($state, '_'))),
                                Repeater::make('languageStrings')
                                    ->extraAttributes(['class' => 'inline-repeater'])
                                    ->label('')
                                    ->columnSpan(2)
                                    ->addable(false)
                                    ->deletable(false)
                                    ->grid(2)
                                    ->relationship('languageStrings')
                                    ->saveRelationshipsWhenHidden(true)
                                    ->schema([
                                        Hidden::make('locale_id'),
                                        Hidden::make('language_string_type_id'),

                                        TextInput::make('text')
                                            ->required()
                                            ->label(fn(Get $get) => LanguageStringType::find($get('language_string_type_id'))->name . ' - ' . Locale::find($get('locale_id'))->language_label),
                                    ])
                                    ->default($defaultLanguageStringState)
                                    ->live(),

                            ])->live(),
                        Fieldset::make('Choice List')
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                            ])
                            ->live()
                            ->relationship('choiceList')
                            ->visible(fn(Get $get) => $get('type') === 'select_one' || $get('type') === 'select_multiple')
                            ->schema([
                                Hidden::make('xlsform_module_version_id')->default($this->xlsformModuleVersion->id)->live(),
                                Hidden::make('list_name')->live()
                                    ->formatStateUsing(fn(Get $get) => $get('../name') . '_choices'),
                                TableRepeater::make('choiceListEntries')
                                    ->relationship('choiceListEntries')
                                    ->headers([
                                        Header::make('name')->label('Name'),
                                        ...$choiceListHeaders,
                                    ])
                                    ->schema([
                                        TextInput::make('name')->required(),
                                        Hidden::make('owner_id')->default(HelperService::getCurrentOwner()->id),
                                        Repeater::make('languageStrings')
                                            ->extraAttributes(['class' => 'inline-repeater'])
                                            ->label('')
                                            ->addable(false)
                                            ->deletable(false)
                                            ->relationship('languageStrings')
                                            ->saveRelationshipsWhenHidden(true)
                                            ->schema([
                                                Hidden::make('locale_id'),
                                                Hidden::make('language_string_type_id'),

                                                TextInput::make('text')
                                                    ->label('test'),
                                            ])
                                            ->default($defaultChoiceLanguageStringState)
                                            ->live(),
                                    ]),
                            ]),

                    ]),
            ])

            // show form content in modal popup
            ->actions([

                Action::make('view_edit_question')
                    ->label('VIEW/EDIT QUESTION')
                    ->icon('heroicon-m-pencil')
                    ->button()
                    ->color('blue')
                    // set more horizontal space for modal popup
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    // fill the form with existing data
                    // Question: why the changes of type and name cannot be saved into survey_rows record?
                    ->fillForm(fn(SurveyRow $record): array => [
                        'xlsform_module_version_id' => $this->xlsformModuleVersion->id,
                        'id' => $record->id,
                        'type' => $record->type,
                        'name' => $record->name,
                    ])
                    ->form([
                        Hidden::make('xlsform_module_version_id')->default($this->xlsformModuleVersion->id)->live(),
                        Hidden::make('id'),
                        Fieldset::make('Question Information')
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                            ])
                            ->schema([
                                Select::make('type')->options([
                                    'select_one' => 'select_one',
                                    'select_multiple' => 'select_multiple',
                                    'decimal' => 'decimal',
                                    'integer' => 'integer',
                                    'text' => 'text',
                                ])->live()
                                    ->required(),
                                TextInput::make('name')->label('Variable Name')->live()->required()
                                    ->dehydrateStateUsing(fn($state): string => Str::lower(Str::slug($state, '_'))),
                                Repeater::make('languageStrings')
                                    ->extraAttributes(['class' => 'inline-repeater'])
                                    ->label('')
                                    ->columnSpan(2)
                                    ->addable(false)
                                    ->deletable(false)
                                    ->grid(2)
                                    ->relationship('languageStrings')
                                    ->saveRelationshipsWhenHidden(true)
                                    ->schema([
                                        Hidden::make('locale_id'),
                                        Hidden::make('language_string_type_id'),

                                        TextInput::make('text')
                                            ->required()
                                            ->label(fn(Get $get) => LanguageStringType::find($get('language_string_type_id'))->name . ' - ' . Locale::find($get('locale_id'))->language_label),
                                    ])
                                    ->default($defaultLanguageStringState)
                                    ->live(),

                            ])->live(),
                        Fieldset::make('Choice List')
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                            ])
                            ->live()
                            ->relationship('choiceList')
                            ->visible(fn(Get $get) => $get('type') === 'select_one' || $get('type') === 'select_multiple')
                            ->schema([
                                Hidden::make('xlsform_module_version_id')->default($this->xlsformModuleVersion->id)->live(),
                                Hidden::make('list_name')->live()
                                    ->formatStateUsing(fn(Get $get) => $get('../name') . '_choices'),
                                TableRepeater::make('choiceListEntries')
                                    ->relationship('choiceListEntries')
                                    ->headers([
                                        Header::make('name')->label('Name'),
                                        ...$choiceListHeaders,
                                    ])
                                    ->schema([
                                        TextInput::make('name')->required(),
                                        Hidden::make('owner_id')->default(HelperService::getCurrentOwner()->id),
                                        Repeater::make('languageStrings')
                                            ->extraAttributes(['class' => 'inline-repeater'])
                                            ->label('')
                                            ->addable(false)
                                            ->deletable(false)
                                            ->relationship('languageStrings')
                                            ->saveRelationshipsWhenHidden(true)
                                            ->schema([
                                                Hidden::make('locale_id'),
                                                Hidden::make('language_string_type_id'),

                                                TextInput::make('text')
                                                    ->label('test'),
                                            ])
                                            ->default($defaultChoiceLanguageStringState)
                                            ->live(),
                                    ]),
                            ]),

                    ])
                    // save changes of type and name to survey_rows record after user clicking modal popup form "Submit" button
                    ->action(function (array $data) {
                        $surveyRow = SurveyRow::find($data['id']);

                        $surveyRow->type = $data['type'];
                        $surveyRow->name = $data['name'];

                        $surveyRow->save();
                    }),


                // add "DELETE QUESTION" button in table row instead of inside modal popup
                DeleteAction::make()
                    ->label('DELETE QUESTION')
                    ->button()
                    ->modalHeading('Delete Question'),

            ]);
    }

    public function render()
    {
        return view('livewire.local-indicator-question-form');
    }

    public function saveFormData()
    {
        // save state and relationships via normal Filament process.
        $this->form->getState();
    }
}
