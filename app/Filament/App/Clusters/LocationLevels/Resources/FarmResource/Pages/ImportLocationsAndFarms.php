<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\FarmResource\Pages;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Actions\Action;
use App\Services\HelperService;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\HeadingRowImport;
use Filament\Forms\Components\TextInput;
use App\Models\SampleFrame\LocationLevel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Http\Client\RequestException;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Platform;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;
use Stats4sd\FilamentOdkLink\Jobs\UpdateXlsformTitleInFile;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;
use Illuminate\Contracts\Container\BindingResolutionException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;


class ImportLocationsAndFarms extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static string $resource = FarmResource::class;

    protected static string $view = 'filament.app.clusters.location-levels.resources.farm-resource.pages.import-locations-and-farms';

    // Code segment belongs to superclass ExcelImportAction starts here...

    protected ?string $disk = null;

    protected function getDisk()
    {
        return $this->disk ?: config('filesystems.default');
    }

    // Code segment belongs to superclass ExcelImportAction ends here...


    public function mount(): void
    {
        $this->form->fill();
    }

    // add a "Save Changes" button to submit the form
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    // add a function to handle the submitted form
    public function save(): void
    {
        ray('save()...');

        try {
            // get the submitted form data for further processing
            $data = $this->form->getState();

            ray($data);

            // send notification
            Notification::make()
                ->success()
                ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
                ->send();
        } catch (Halt $exception) {
            return;
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([

                    // Step 1
                    Wizard\Step::make('Upload your farm list excel file')
                        ->schema([

                            // Question: is the file upload works for ExcelImportAction's subclass only?
                            FileUpload::make('upload')
                                ->label('Location Levels and Farm List Excel Data')
                                ->helperText('Please make sure your data is in the first worksheet of the Excel file, and that the first row contains the column headers.')
                                ->disk($this->getDisk())
                                ->columns()
                                ->required()
                                ->live()
                                ->preserveFilenames()
                                ->afterStateUpdated(function (?TemporaryUploadedFile $state, Set $set) {
                                    $headings = (new HeadingRowImport)->toArray($state?->getRealPath());

                                    // $headings is an array(sheets) of arrays(headers)
                                    // We only want the first sheet
                                    $headings = $headings[0][0];

                                    $set('header_columns', $headings ?? []);
                                }),

                        ])
                        ->afterValidation(function () {
                            ray('step 1 afterValidation');
                        }),


                    // Step 2
                    Wizard\Step::make('Map columns to location levels')
                        ->schema(

                            [
                                Section::make('Column Mapping')
                                    ->columns(2)
                                    ->schema(function ($livewire) {

                                        $hasFarmLevel = LocationLevel::where('has_farms', 1)->first();
                                        $currentLevel = $hasFarmLevel;
                                        $parents = collect([]);

                                        while ($currentLevel->parent) {
                                            $parents->push($currentLevel->parent);
                                            $currentLevel = $currentLevel->parent;
                                        }

                                        $parentQuestions = $parents->reverse()->map(callback: function ($parent) {
                                            return collect([
                                                Select::make("parent_{$parent->id}_code_column")
                                                    ->label(fn($livewire) => "Which column contains the {$parent->name} unique code?")
                                                    ->options(fn(Get $get) => $get('header_columns'))
                                                    ->notIn(['na'])
                                                    ->required(),
                                                Select::make("parent_{$parent->id}_name_column")
                                                    ->label(fn($livewire) => "Which column contains the {$parent->name} name?")
                                                    ->options(fn(Get $get) => $get('header_columns'))
                                                    ->notIn(['na'])
                                                    ->required(),
                                            ]);
                                        })->flatten();

                                        $currentLevelQuestions = collect([
                                            Select::make('code_column')
                                                ->label(fn($livewire) => "Which column contains the {$hasFarmLevel->name} unique code?")
                                                ->options(fn(Get $get) => $get('header_columns'))
                                                ->notIn(['na'])
                                                ->required(),
                                            Select::make('name_column')
                                                ->label(fn($livewire) => "Which column contains the {$hasFarmLevel->name} name?")
                                                ->options(fn(Get $get) => $get('header_columns'))
                                                ->notIn(['na'])
                                                ->required(),
                                        ]);

                                        return $parentQuestions->merge($currentLevelQuestions)->toArray();
                                    }),

                                Select::make('override')
                                    ->label('Do you want to replace all locations with this import? (This will delete all existing locations from all location levels!)')
                                    ->options([
                                        'no' => 'No',
                                        'yes' => 'Yes',
                                    ])
                                    ->helperText('If you select "No", all existing locations will be kept. If you select "Yes", all existing locations will be deleted and replaced with the data from this import.')
                                    ->default('no'),

                                Hidden::make('header_columns')
                                    ->default(['na' => '~~upload a file to see the headers~~'])
                                    ->live(),
                                Hidden::make('level')
                                    ->default(LocationLevel::where('has_farms', 0)->first()),
                                Hidden::make('user_id')
                                    ->default(fn() => auth()->id()),
                                Hidden::make('owner_id')
                                    ->default(HelperService::getCurrentOwner()->id),
                            ]
                        )
                        ->afterValidation(function () {
                            ray('step 2 afterValidation');
                        }),


                    // Step 3
                    Wizard\Step::make('Map columns to farm')
                        ->schema([

                            Hidden::make('header_columns')
                                ->default(['na' => '~~upload a file to see the column headers~~'])
                                ->live(),

                            Section::make('Location')
                                ->schema([

                                    // Question: is it possible to have more than one location levels have farms?
                                    // can we set default value to simplify the import process?
                                    Select::make('location_level_id')
                                        ->label('Which location level are the farms linked to?')
                                        ->options(
                                            LocationLevel::where('has_farms', true)->get()->pluck('name', 'id')
                                        )
                                        ->placeholder('Select a location level')
                                        ->helperText('For many sampling strategies, this will be obvious (the lowest level. It may be less obvious when there are different hierarchies of locations in different places.')
                                        ->live(),

                                    Select::make('location_code_column')
                                        ->options(fn(Get $get) => $get('header_columns'))
                                        ->label(fn(Get $get) => 'Which column contains the ' . (LocationLevel::find($get('location_level_id'))?->name ?? 'location') . ' unique code?')
                                        ->placeholder('Select a column'),
                                ]),

                            Section::make('Farm Information')
                                ->columns(1)
                                ->schema([
                                    Select::make('farm_code_column')
                                        ->label('Which column contains the farm unique code?')
                                        ->placeholder('Select a column')
                                        ->helperText('e.g. farm_id or farm_code')
                                        ->live()
                                        ->options(fn(Get $get) => $get('header_columns')),

                                    CheckboxList::make('farm_identifiers')
                                        ->label('Are there any additional columns that contain identifiers for the farm? Tick all that apply.')
                                        ->helperText('For example: family name, farm name, telephone numbers, etc. These are columns that can be useful for enumerators or project team members to identify the farm, but that should not be shared outside the project for data protection purposes.')
                                        ->options(fn(Get $get): array => $get('header_columns'))
                                        ->disableOptionWhen(
                                            fn(string $value, Get $get): bool => $value === (string) $get('farm_code_column') ||
                                                collect($get('farm_properties'))->contains($value) ||
                                                $value === 'na'
                                        )
                                        ->live()
                                        ->columnSpanFull(),

                                    CheckboxList::make('farm_properties')
                                        ->label('Are there any additional columns that contain properties of the farm? Tick all that apply.')
                                        ->helperText('These are not identifiers, but are properties of the farm that are useful for analysis. For example: size of the farm, year of first engagement, etc. These are columns that can potentially be shared outside the project for analysis purposes.')
                                        ->options(fn(Get $get) => $get('header_columns'))
                                        ->disableOptionWhen(
                                            fn(string $value, Get $get): bool => $value === (string) $get('farm_code_column') ||
                                                collect($get('farm_identifiers'))->contains($value) ||
                                                $value === 'na'
                                        )
                                        ->live()
                                        ->columnSpanFull(),

                                    Hidden::make('owner_id')
                                        ->default(HelperService::getCurrentOwner()->id),

                                ]),

                        ])
                        ->afterValidation(function () {
                            ray('step 3 afterValidation');
                        }),


                ])

            ])
            ->statePath('data');
    }
}
