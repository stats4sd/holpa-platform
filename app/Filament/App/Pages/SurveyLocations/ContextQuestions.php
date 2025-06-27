<?php

namespace App\Filament\App\Pages\SurveyLocations;

use App\Models\Team;
use App\Services\HelperService;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\LanguageStringType;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;

class ContextQuestions extends Page implements HasActions, HasForms, HasTable
{

    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.survey-locations.context-questions';

    public Team $team;
    public XlsformModuleVersion $xlsformModuleVersion;

    public function mount(): void
    {
        $team = HelperService::getCurrentOwner();

        if ($team === null) {
            abort(404);
        }

        $this->team = $team;

        $this->xlsformModuleVersion = $this->team->localContextModuleVersion->load(['surveyRows.languageStrings', 'surveyRows.choiceList.choiceListEntries.languageStrings']);

        $this->form->fill($this->xlsformModuleVersion->toArray());
    }

    public function table(Table $table): Table
    {

        $locales = $this->team->locales;

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

        $questionForm = [
            Hidden::make('xlsform_module_version_id')->default($this->xlsformModuleVersion->id)->live(),
            Hidden::make('id'),
            Fieldset::make('Question Information')
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                    'lg' => 2,
                ])
                ->schema([
                    Hidden::make('row_number')
                        ->default(function () {

                            // find the largest row_number of survey_rows records
                            $number = $this->xlsformModuleVersion->surveyRows->last()?->row_number ?? 0;

                            return $number + 1;


                        }),
                    Select::make('type')->options([
                        'select_one' => 'select_one',
                        'select_multiple' => 'select_multiple',
                        'decimal' => 'decimal',
                        'integer' => 'integer',
                        'text' => 'text',
                    ])
                        ->afterStateHydrated(function (Select $component, ?string $state) {
                            if (Str::startsWith($state, 'select_one')) {
                                $component->state('select_one');
                            }

                            if (Str::startsWith($state, 'select_multiple')) {
                                $component->state('select_multiple');
                            }
                        })
                        ->dehydrateStateUsing(function ($state, Get $get) {
                            if ($state === 'select_one' || $state === 'select_multiple') {
                                $state = $state . ' ' . Str::lower(Str::slug($get('name'), separator: '_'));
                            }

                            return $state;
                        })
                        ->live()
                        ->required(),
                    TextInput::make('name')->label('Variable Name')->live()->required()
                        ->helperText('The variable name should only have alphanumeric characters or underscores. Any spaces will be automatically replaced with underscores when saving this question.')
                        ->dehydrateStateUsing(fn($state): string => Str::lower(Str::slug($state, separator: '_'))),
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
                                ->required(fn(Get $get) => LanguageStringType::find($get('language_string_type_id'))->name === 'label')
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
                ->visible(fn(Get $get) => Str::startsWith($get('type'), 'select_one') || Str::startsWith($get('type'), 'select_multiple'))
                ->relationship('choiceList')
                ->saveRelationshipsBeforeChildrenUsing(fn(Fieldset $component) => $component->saveRelationships())
                ->schema([
                    Hidden::make('xlsform_module_version_id')->default($this->xlsformModuleVersion->id)->live(),
                    Hidden::make('list_name')->live()
                        ->dehydrateStateUsing(fn(Get $get) => $get('../name') . '_choices'),
                    Repeater::make('choiceListEntries')
                        ->relationship('choiceListEntries')
                        ->label('Options list for the select question')
                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                        ->schema([
                            TextInput::make('name')
                                ->label('Option name')
                                ->helperText('This is the "name" column in ODK, and is the value that appears in the data when the option is selected')
                                ->required(),
                            Hidden::make('owner_id')->default(HelperService::getCurrentOwner()->id),
                            Repeater::make('languageStrings')
                                ->extraAttributes(['class' => 'inline-repeater'])
                                ->columnSpan(2)
                                ->grid(2)
                                ->label('')
                                ->addable(false)
                                ->deletable(false)
                                ->relationship('languageStrings')
                                ->saveRelationshipsWhenHidden(true)
                                ->schema([
                                    Hidden::make('locale_id'),
                                    Hidden::make('language_string_type_id'),

                                    TextInput::make('text')
                                        ->required()
                                        ->label(fn(Get $get) => LanguageStringType::find($get('language_string_type_id'))->name . ' - ' . Locale::find($get('locale_id'))->language_label),
                                ])
                                ->default($defaultChoiceLanguageStringState)
                                ->live(),
                        ]),
                ]),

        ];

        return $table
            ->query(
                fn() => SurveyRow::query()
                    ->where('xlsform_module_version_id', $this->xlsformModuleVersion->id)
                    // sort record by row_number, to reflect the user defined ordering by drag and drop
                    ->orderBy('row_number'),
            )
            ->columns([
                TextColumn::make('type')->label('Question Type'),
                TextColumn::make('name')->label('Variable Name')->wrap(),
                // fix to show default label as string instead of a JSON array
                TextColumn::make('defaultLabel.text')->label('Default Label'),
            ])
            // allow user to change the ordering by drag and drop
            ->reorderable('row_number')
            ->reorderRecordsTriggerAction(
                fn(Action $action, bool $isReordering) => $action
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
                    ->modalSubmitAction(fn(StaticAction $action) => $action
                        ->extraAttributes(['class' => 'buttona shadow-none !ring-0 border-0']))
                    ->modalCancelAction(fn(StaticAction $action) => $action
                        ->extraAttributes(['class' => 'buttonb shadow-none !ring-0 ']))
                    ->createAnother(false)
                    ->form($questionForm),
            ])

            // show form content in modal popup
            ->actions([

                Action::make('view_edit_question')
                    ->label('VIEW/EDIT QUESTION')
                    ->icon('heroicon-m-pencil')
                    ->extraAttributes(['class' => 'py-2 shadow-none'])
                    ->button()
                    ->color('blue')
                    // disable editing uploaded custom questions
                    // Note: we use survey_rows.path to determine if a custom question is uploaded or not now,
                    // when we start using survey_rows.path for matching submissions to SurveyRow entries,
                    // we will need to create a new column as a flag for indication
                    ->disabled(fn(SurveyRow $record) => $record->path != null)
                    // set more horizontal space for modal popup
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    // fill the form with existing data
                    ->fillForm(fn(SurveyRow $record): array => [
                        'xlsform_module_version_id' => $this->xlsformModuleVersion->id,
                        'id' => $record->id,
                        'type' => $record->type,
                        'name' => $record->name,
                    ])
                    ->form($questionForm),
                    // save changes of type and name to survey_rows record after user clicking modal popup form "Submit" button
//                    ->action(function (array $data) {
//                        $surveyRow = SurveyRow::find($data['id']);
//
//                        $surveyRow->type = $data['type'];
//                        $surveyRow->name = $data['name'];
//
//                        $surveyRow->save();
//                    }),

                // add "DELETE QUESTION" button in table row instead of inside modal popup
                DeleteAction::make()
                    ->label('DELETE QUESTION')
                    ->extraAttributes(['class' => 'py-2 shadow-none'])
                    ->button()
                    ->modalHeading('Delete Question'),

            ]);
    }


}
