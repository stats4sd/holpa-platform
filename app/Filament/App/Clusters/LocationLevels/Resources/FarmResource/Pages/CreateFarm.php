<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\FarmResource\Pages;

use Filament\Forms\Get;
use App\Services\HelperService;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use App\Models\SampleFrame\LocationLevel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Http\Client\RequestException;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Platform;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;
use Stats4sd\FilamentOdkLink\Jobs\UpdateXlsformTitleInFile;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;
use Illuminate\Contracts\Container\BindingResolutionException;
use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource;

class CreateFarm extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = FarmResource::class;

    // Code segment belongs to superclass ExcelImportAction starts here...

    protected ?string $disk = null;

    protected function getDisk()
    {
        return $this->disk ?: config('filesystems.default');
    }

    // Code segment belongs to superclass ExcelImportAction ends here...

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->modalWidth('3xl')
            ->modalDescription('Import Farms from an Excel file. The first worksheet of the Excel file should contain the data to import. The first row of the worksheet should contain the column headings. You must have already created or imported the locations that the farms will be associated with.');
    }


    public function getSteps(): array
    {
        return [

            // Step 1
            Step::make('Upload your farm list excel file')
                ->schema([

                    // Question: is the file upload works for ExcelImportAction's subclass only?
                    // FileUpload::make('upload')
                    //     ->label(fn($livewire) => str($livewire->getRecord()->name)->plural()->title() . ' ' . 'Excel Data')
                    //     ->disk($this->getDisk())
                    //     ->columns()
                    //     ->required()
                    //     ->live()
                    //     ->afterStateUpdated(function (?TemporaryUploadedFile $state, Set $set) {
                    //         $headings = (new HeadingRowImport)->toArray($state->getRealPath());

                    //         // $headings is an array(sheets) of arrays(headers)
                    //         // We only want the first sheet
                    //         $headings = $headings[0][0];

                    //         $set('header_columns', $headings ?? []);
                    //     }),

                ]),


            // Step 2
            Step::make('Map columns to location levels')
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
                ->afterValidation(function (Get $get) {

                    $xlsformTemplate = XlsformTemplate::create([
                        'title' => $get('title'),
                    ]);

                    $files = $get('xlsfile');

                    $xlsformTemplate->addMedia(collect($files)->first())->toMediaCollection('xlsform_file');

                    // this was being triggered on afterCreate. Call it here instead/as well.
                    $this->processRecord($xlsformTemplate);

                    return redirect($this->getResource()::getUrl('edit', ['record' => $xlsformTemplate]));
                }),


            // Step 3
            Step::make('Map columns to farm')
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

                ]),

        ];
    }

    /**
     * @throws RequestException
     * @throws BindingResolutionException
     */
    protected function afterCreate(): void
    {
        $test = $this->getRecord();

        assert($test instanceof XlsformTemplate);
        $this->processRecord($test);
    }

    // /**
    //  * @throws RequestException
    //  * @throws BindingResolutionException
    //  */
    // protected function processRecord(XlsformTemplate $record): XlsformTemplate
    // {
    //     $odkLinkService = app()->make(OdkLinkService::class);

    //     $record->owner()->associate(Platform::first());
    //     $record->saveQuietly();

    //     // update form title in xlsfile to match user-given title
    //     UpdateXlsformTitleInFile::dispatchSync($record);

    //     $record->refresh();
    //     $record->deployDraft($odkLinkService);
    //     $record->getRequiredMedia($odkLinkService);

    //     // TODO: We need to do the extract section when create and edit
    //     $record->extractSections();

    //     return $record;
    // }

    // public function action(Closure|string|null $action): static
    // {
    //     if ($action !== 'importData') {
    //         throw new RuntimeException('You cannot override the action for this plugin');
    //     }

    //     $this->action = $this->importData();

    //     return $this;
    // }

    // public function importData(): Closure
    // {
    //     return function (array $data, $livewire): bool {

    //         // create import record - for review and error tracking by users
    //         $import = Import::create([
    //             'team_id' => HelperService::getCurrentOwner()->id,
    //             'model_type' => Farm::class,
    //         ]);

    //         $import->addMedia(Storage::path($data['upload']))->toMediaCollection();

    //         $data['import_id'] = $import->id;

    //         // setup importer
    //         $importObject = new $this->importClass($data);

    //         // run import
    //         Excel::import($importObject, $import->getFirstMediaPath());

    //         return true;
    //     };
    // }
}
