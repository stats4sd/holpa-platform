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
use Illuminate\Http\Client\RequestException;

use Stats4sd\FilamentOdkLink\Models\OdkLink\Platform;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;
use Stats4sd\FilamentOdkLink\Jobs\UpdateXlsformTitleInFile;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;
use Illuminate\Contracts\Container\BindingResolutionException;
use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource;
use Stats4sd\FilamentOdkLink\Filament\OdkAdmin\Resources\XlsformTemplateResource;


class CreateFarm extends CreateRecord
{
    protected static string $resource = FarmResource::class;

    use CreateRecord\Concerns\HasWizard;

    public function getSteps(): array
    {
        return [
            Step::make('1. Xlsform')
                ->description('Upload your XLSForm file and give it a title')
                ->schema(
                    // XlsformTemplateResource::getCreateFields(),

                    [
                        Section::make('Column Mapping')
                            ->columns(2)
                            ->schema(function ($livewire) {
                                // TODO: add file upload compoenent



                                // find the location level that has farms
                                // $currentLevel = $livewire->getRecord();
                                $currentLevel = LocationLevel::where('has_farms', true)->first();

                                $parents = collect([]);

                                while ($currentLevel->parent) {
                                    $parents->push($currentLevel->parent);
                                    $currentLevel = $currentLevel->parent;
                                }

                                $parentQuestions = $parents->reverse()->map(callback: function ($parent) {
                                    ray($parent->name);

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

                                // Question: why current level is District? It should be Village
                                ray($currentLevel->name);

                                $currentLevelQuestions = collect([
                                    Select::make('code_column')
                                        ->label(fn($livewire) => "Which column contains the {$currentLevel->name} unique code?")
                                        ->options(fn(Get $get) => $get('header_columns'))
                                        ->notIn(['na'])
                                        ->required(),
                                    Select::make('name_column')
                                        ->label(fn($livewire) => "Which column contains the {$currentLevel->name} name?")
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
                            ->default(fn($livewire) => $livewire->getRecord()),
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

            Step::make('2. Add Media Files')
                ->description('Add any static media required by the form')
                ->schema([]),
            Step::make('3. Link Required Datasets')
                ->description('Add / link external datasets for lookup tables')
                ->schema([]),
            Step::make('4. Review Xlsform Structure')
                ->description('How should the collected data be handled?')
                ->schema([]),
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

    /**
     * @throws RequestException
     * @throws BindingResolutionException
     */
    protected function processRecord(XlsformTemplate $record): XlsformTemplate
    {
        $odkLinkService = app()->make(OdkLinkService::class);

        $record->owner()->associate(Platform::first());
        $record->saveQuietly();

        // update form title in xlsfile to match user-given title
        UpdateXlsformTitleInFile::dispatchSync($record);

        $record->refresh();
        $record->deployDraft($odkLinkService);
        $record->getRequiredMedia($odkLinkService);

        // TODO: We need to do the extract section when create and edit
        $record->extractSections();

        return $record;
    }
}
