<?php

namespace App\Livewire\Lisp;

use App\Models\Holpa\LocalIndicator;
use App\Services\HelperService;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Actions\StaticAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Component;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\LanguageStringType;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;

class LocalIndicatorQuestionForm extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithForms;
    use InteractsWithTable;

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
                Header::make('label_'.$locale->id)->label('Label - '.$locale->language_label),
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
                fn () => SurveyRow::query()
                    ->where('xlsform_module_version_id', $this->xlsformModuleVersion->id)
                    // sort record by row_number, to reflect the user defined ordering by drag and drop
                    ->orderBy('row_number'),
            )
            ->columns([
                TextColumn::make('type')->label('Question Type'),
                TextColumn::make('name')->label('Variable Name')->wrap(),
                TextColumn::make('defaultLabel')->label('Default Label')->wrap(),
            ])
            // allow user to change the ordering by drag and drop
            ->reorderable('row_number')
            ->reorderRecordsTriggerAction(
                fn (Action $action, bool $isReordering) => $action
                    ->button()
                    ->label($isReordering ? 'Disable reordering' : 'Enable reordering')
                    ->extraAttributes(['class' => 'buttona border-0 !ring-0 !shadow-none -ml-2  mb-4']))
            ->paginated(false)

            // add "create new record" button in table header
            ->headerActions([
                CreateAction::make()
                    ->label('ADD QUESTION')
                    ->icon('heroicon-m-plus')
                    ->button()
                    ->color('danger')
                    ->extraAttributes(['class' => 'add_questions_btn'])
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    ->modalHeading('ADD QUESTION')
                    ->extraModalWindowAttributes(['class' => 'add_questions_modal'])
                    ->modalSubmitAction(fn (StaticAction $action) => $action
                        ->extraAttributes(['class' => 'buttona shadow-none !ring-0 border-0']))
                    ->modalCancelAction(fn (StaticAction $action) => $action
                        ->extraAttributes(['class' => 'buttonb shadow-none !ring-0 ']))
                    ->createAnother(false)
                    ->mutateFormDataUsing(function (array $data): array {
                        // find the largest row_number of survey_rows records
                        $lastRowNumber = $this->xlsformModuleVersion->surveyRows->count() != 0 ? $this->xlsformModuleVersion->surveyRows->last()->row_number : 0;

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
                                    ->dehydrateStateUsing(fn ($state): string => Str::lower(Str::slug($state, '_'))),
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
                                            ->label(fn (Get $get) => LanguageStringType::find($get('language_string_type_id'))->name.' - '.Locale::find($get('locale_id'))->language_label),
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
                            ->visible(fn (Get $get) => $get('type') === 'select_one' || $get('type') === 'select_multiple')
                            ->schema([
                                Hidden::make('xlsform_module_version_id')->default($this->xlsformModuleVersion->id)->live(),
                                Hidden::make('list_name')->live()
                                    ->formatStateUsing(fn (Get $get) => $get('../name').'_choices'),
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
                                                    ->label(''),
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
                    ->extraAttributes(['class' => 'py-2 shadow-none'])
                    ->button()
                    ->color('blue')
                    // set more horizontal space for modal popup
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    // fill the form with existing data
                    // Question: why the changes of type and name cannot be saved into survey_rows record?
                    ->fillForm(fn (SurveyRow $record): array => [
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
                                    ->dehydrateStateUsing(fn ($state): string => Str::lower(Str::slug($state, '_'))),
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
                                            ->label(fn (Get $get) => LanguageStringType::find($get('language_string_type_id'))->name.' - '.Locale::find($get('locale_id'))->language_label),
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
                            ->visible(fn (Get $get) => $get('type') === 'select_one' || $get('type') === 'select_multiple')
                            ->schema([
                                Hidden::make('xlsform_module_version_id')->default($this->xlsformModuleVersion->id)->live(),
                                Hidden::make('list_name')->live()
                                    ->formatStateUsing(fn (Get $get) => $get('../name').'_choices'),
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
                    ->extraAttributes(['class' => 'py-2 shadow-none'])
                    ->button()
                    ->modalHeading('Delete Question'),

            ]);
    }

    // keep the original form() function for further testing after revising front end styling
    public function form(Form $form): Form
    {
        $locales = $this->localIndicator->team->locales;

        $choiceListHeaders = $locales->map(function (Locale $locale) {
            return [
                Header::make('label_'.$locale->id)->label('Label - '.$locale->language_label),
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

        // issues found in the original form during testing:
        // 1. when create a new question, choice_lists.list_name is always "_choices". list_name is constructed when name is empty at the very beginning.
        // 2. choice_list_entries records not saved after saving a newly created question
        // 3. after deleting question, survey_rows record is deleted. But the related choice_lists records remain in database
        //
        // possible solution:
        // 1. construct list_name value before save
        // 2. is choice_lists record created yet? Should we create a choice_lists record when user changes type to select_one or select_multiple?
        // 3. add onDelete(), so that choice_lists record will be deleted when survey_rows record is deleted

        return $form->statePath('data')
            ->model($this->xlsformModuleVersion)
            ->schema([
                Repeater::make('surveyRows')
                    ->relationship('surveyRows')
                    ->deleteAction(fn ($action) => $action->button()->label('Delete Question'))
                    ->reorderableWithDragAndDrop()
                    ->reorderAction(fn ($action) => $action->button()->label('Drag to Reorder Questions'))
                    ->addActionLabel('Add Question')
                    ->label('')
                    ->itemLabel(fn (array $state) => $state['name'] ?? 'New Question')
                    ->orderColumn('row_number')
                    ->schema([
                        Hidden::make('xlsform_module_version_id')->default($this->xlsformModuleVersion->id)->live(),
                        Fieldset::make('Question Information')
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 4,
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
                                    ->dehydrateStateUsing(fn ($state): string => Str::lower(Str::slug($state, '_'))),
                                Repeater::make('languageStrings')
                                    ->extraAttributes(['class' => 'inline-repeater'])
                                    ->label('')
                                    ->columnSpan(4)
                                    ->addable(false)
                                    ->deletable(false)
                                    ->grid(4)
                                    ->relationship('languageStrings')
                                    ->saveRelationshipsWhenHidden(true)
                                    ->schema([
                                        Hidden::make('locale_id'),
                                        Hidden::make('language_string_type_id'),

                                        TextInput::make('text')
                                            ->required()
                                            ->label(fn (Get $get) => LanguageStringType::find($get('language_string_type_id'))->name.' - '.Locale::find($get('locale_id'))->language_label),
                                    ])
                                    ->default($defaultLanguageStringState)
                                    ->live(),

                            ])->live(),
                        Fieldset::make('Choice List')
                            ->live()
                            ->relationship('choiceList')
                            ->visible(fn (Get $get) => $get('type') === 'select_one' || $get('type') === 'select_multiple')
                            ->schema([
                                Hidden::make('xlsform_module_version_id')->default($this->xlsformModuleVersion->id)->live(),

                                // It is executed at the very beginning.
                                // For create a new question, list_name is "_choices" because name field is empty.
                                // For updating a question, list_name is [name]_choices. But it does not change if user updated name field before save.
                                //
                                // For proper operation, we may need to construct list_name value before save.
                                //
                                // TODO: change list_name from [name]_choices to [survey_rows.id]_[name]_choices

                                Hidden::make('list_name')->live()
                                    ->formatStateUsing(fn (Get $get) => $get('../name').'_choices'),

                                TableRepeater::make('choiceListEntries')
                                    ->relationship('choiceListEntries')
                                    // avoid showing a pre-created empty record on screen by setting default items to 0,
                                    // but it does not help to resolve issue that choice list entries record not saved when creating new question.
                                    // it looks like choice list entrties record cannot be saved before choice list record is created.
                                    ->defaultItems(0)
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
            ]);
    }

    public function render()
    {
        return view('livewire.lisp.local-indicator-question-form');
    }

    public function saveFormData()
    {
        // save state and relationships via normal Filament process.
        $this->form->getState();
    }
}
