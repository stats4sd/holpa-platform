<?php

namespace App\Livewire;

use App\Models\Team;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;
use Stats4sd\FilamentOdkLink\Services\HelperService;

class UploadCustomIndicators extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Team $team;
    public ?array $custom_indicators_hh = null;
    public ?array $custom_indicators_fw = null;
    public ?Media $uploadedFileHH = null;
    public ?Media $uploadedFileFW = null;

    public function mount(): void
    {
        $this->team = HelperService::getCurrentOwner();
        $this->form->fill();

        $this->uploadedFileHH = $this->team->xlsform_hh_module_version
                                    ? $this->team->xlsform_hh_module_version->getMedia('custom_indicators_hh')->first()
                                    : null;
        $this->uploadedFileFW = $this->team->xlsform_fw_module_version
                                    ? $this->team->xlsform_fw_module_version->getMedia('custom_indicators_fw')->first()
                                    : null;
    }

    public function table(Table $table): Table
    {
        $moduleVersionIds = [];

        if ($this->team->xlsformHhModuleVersion) {
            $moduleVersionIds[] = $this->team->xlsformHhModuleVersion->id;
        }

        if ($this->team->xlsformFwModuleVersion) {
            $moduleVersionIds[] = $this->team->xlsformFwModuleVersion->id;
        }

        return $table
        ->query(Media::query()
            ->whereIn('model_id', $moduleVersionIds)
            ->where('model_type', XlsformModuleVersion::class)
            ->whereIn('collection_name', ['custom_indicators_hh', 'custom_indicators_fw'])
        )
        ->columns([
            TextColumn::make('collection_name')
                ->label('Survey')
                ->formatStateUsing(function (string $state) {
                    return $state === 'custom_indicators_hh' ? 'Household' : ($state === 'custom_indicators_fw' ? 'Fieldwork' : $state);
                }),
            TextColumn::make('file_name')
                ->label('Uploaded file')
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Upload date')
                ->dateTime('Y/m/d'),
        ])
        ->actions([
            Action::make('replace')
                ->label('Replace File')
                ->modalHeading('Replace custom indicators file')
                ->color('orange')
                ->button()
                ->form(function (Media $record) {
                    $collection = $record->collection_name;

                    if ($collection === 'custom_indicators_hh') {
                        return [
                            FileUpload::make('custom_indicators_hh')
                                ->label('Household Indicators')
                                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                                ->maxSize(10240)
                                ->maxFiles(1)
                                ->preserveFilenames(),
                        ];
                    } elseif ($collection === 'custom_indicators_fw') {
                        return [
                            FileUpload::make('custom_indicators_fw')
                                ->label('Fieldwork Indicators')
                                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                                ->maxSize(10240)
                                ->maxFiles(1)
                                ->preserveFilenames(),
                        ];
                    }

                    return [];
                })
                ->action(function (array $data, Media $record) {
                    // Replace file
                    $record->update([
                        'file_name' => $data['custom_indicators_hh'] ?? $data['custom_indicators_fw'],
                    ]);
                    // Display success message
                    Notification::make()
                    ->title('Success')
                    ->body('File replaed successfully!')
                    ->success()
                    ->send();
                }),
        ])
        ->paginated(false);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('custom_indicators_hh')
                    ->label('Household Indicators')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                    ->maxSize(10240)
                    ->maxFiles(1)
                    ->preserveFilenames()
                    ->reactive()
                    ->visible(fn() => !$this->uploadedFileHH),
                FileUpload::make('custom_indicators_fw')
                    ->label('Fieldwork Indicators')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                    ->maxSize(10240)
                    ->maxFiles(1)
                    ->preserveFilenames()
                    ->reactive()
                    ->visible(fn() => !$this->uploadedFileFW)
            ])->columns(1);
    }

    public function uploadFiles(): void
    {
        // Ensure at least one file is uploaded
        if ((empty($this->custom_indicators_hh) || !is_array($this->custom_indicators_hh)) &&
            (empty($this->custom_indicators_fw) || !is_array($this->custom_indicators_fw))) {
            $this->addError('missing_file', 'No file has been added.');
            return;
        }

        try {
            // Process household indicators file if uploaded
            if (!empty($this->custom_indicators_hh) && is_array($this->custom_indicators_hh)) {
                $fileHH = reset($this->custom_indicators_hh); // Get the first file
                $this->processFileUpload($fileHH, 'custom_indicators_hh');
            }

            // Process fieldwork indicators file if uploaded
            if (!empty($this->custom_indicators_fw) && is_array($this->custom_indicators_fw)) {
                $fileFW = reset($this->custom_indicators_fw); // Get the first file
                $this->processFileUpload($fileFW, 'custom_indicators_fw');
            }

            // Display success message
            Notification::make()
                ->title('Success')
                ->body('Files uploaded successfully!')
                ->success()
                ->send();

        } catch (Exception $e) {
            $this->addError('custom_indicators', 'An error occurred while uploading one or more files: ' . $e->getMessage());
        }
    }

    private function processFileUpload(TemporaryUploadedFile $file, string $collection): void
    {
        try {
            // Get the original filename
            $originalFilename = $file->getClientOriginalName();

            if ($collection === 'custom_indicators_hh') {

                // Add the uploaded file to the specified media collection
                $this->team->xlsform_hh_module_version->addMedia($file->getRealPath())
                    ->usingName(pathinfo($originalFilename, PATHINFO_FILENAME))
                    ->usingFileName($originalFilename)
                    ->toMediaCollection($collection);

                $this->uploadedFileHH = $this->team->xlsform_hh_module_version->getMedia($collection)->first();

            } elseif ($collection === 'custom_indicators_fw') {

                // Add the uploaded file to the specified media collection
                $this->team->xlsform_fw_module_version->addMedia($file->getRealPath())
                    ->usingName(pathinfo($originalFilename, PATHINFO_FILENAME))
                    ->usingFileName($originalFilename)
                    ->toMediaCollection($collection);

                $this->uploadedFileFW = $this->team->xlsform_fw_module_version->getMedia($collection)->first();

            }

        } catch (Exception $e) {
            $this->addError($collection, 'An error occurred while processing the file: ' . $e->getMessage());
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View|null
    {
        return view('livewire.upload-custom-indicators');
    }
}
