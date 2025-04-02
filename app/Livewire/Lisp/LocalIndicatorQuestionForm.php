<?php

namespace App\Livewire\Lisp;

use App\Models\Holpa\LocalIndicator;
use App\Services\HelperService;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
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
use Illuminate\Support\Str;
use Livewire\Component;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\LanguageStringType;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;

class LocalIndicatorQuestionForm extends Component implements HasForms, HasActions
{

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


    public function form(Form $form): Form
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
                ['locale_id' => $locale->id,
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

        return $form->statePath('data')
            ->model($this->xlsformModuleVersion)
            ->schema([
                Repeater::make('surveyRows')
                    ->relationship('surveyRows')
                    ->deleteAction(fn($action) => $action->button()->label('Delete Question'))
                    ->reorderableWithDragAndDrop()
                    ->reorderAction(fn($action) => $action->button()->label('Drag to Reorder Questions'))
                    ->addActionLabel('Add Question')
                    ->label('')
                    ->itemLabel(fn(array $state) => $state['name'] ?? 'New Question')
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
                                ->dehydrateStateUsing(fn($state): string => Str::lower(Str::slug($state, '_'))),
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
                                            ->label(fn(Get $get) => LanguageStringType::find($get('language_string_type_id'))->name . ' - ' . Locale::find($get('locale_id'))->language_label),
                                    ])
                                    ->default($defaultLanguageStringState)
                                    ->live(),

                            ])->live(),
                        Fieldset::make('Choice List')
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
