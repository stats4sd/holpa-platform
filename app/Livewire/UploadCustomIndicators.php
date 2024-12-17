<?php

namespace App\Livewire;

use Exception;
use App\Models\Team;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomIndicatorImport;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use App\Imports\CustomIndicatorWorkbookImport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UploadCustomIndicators extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Team $team;
    public ?array $custom_indicators_hh = null;
    public ?array $custom_indicators_fw = null;
    public $uploadedFileHH = null;
    public $uploadedFileFW = null;

    public function mount()
    {
        $this->team = Team::find(auth()->user()->latestTeam->id);
        $this->form->fill();
        $this->uploadedFileHH = $this->team->getMedia('custom_indicators_hh')->first();
        $this->uploadedFileFW = $this->team->getMedia('custom_indicators_fw')->first();
    }

    public function table(Table $table): Table
    {
        return $table
        ->query(Media::query()
            ->where('model_id', $this->team->id)
            ->where('model_type', Team::class)
            ->whereIn('collection_name', ['custom_indicators_hh', 'custom_indicators_fw'])
        )
        ->columns([
            TextColumn::make('file_name')
                ->label('Uploaded file')
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Upload date')
                ->dateTime('Y/m/d'),
        ])
        ->actions([
            Action::make('delete')
                ->label('Delete')
                ->color('danger')
                ->button()
                ->requiresConfirmation()
                ->action(function (Media $record) {
                    // Delete the media file
                    $record->delete();

                    // TODO: Delete imported custom indicator data & update the uploaded file details

                    // Display success message
                    Notification::make()
                    ->title('Success')
                    ->body('File deleted successfully!')
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
                    ->preserveFilenames(),
                FileUpload::make('custom_indicators_fw')
                    ->label('Fieldwork Indicators')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                    ->maxSize(10240)
                    ->maxFiles(1)
                    ->preserveFilenames()
            ])->columns(2);
    }

    public function uploadFiles()
    {
        // Ensure at least one file is uploaded
        if ((empty($this->custom_indicators_hh) || !is_array($this->custom_indicators_hh)) &&
            (empty($this->custom_indicators_fw) || !is_array($this->custom_indicators_fw))) {
            $this->addError('custom_indicators', 'Please upload at least one file before proceeding.');
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

    private function processFileUpload(TemporaryUploadedFile $file, string $collection)
    {
        try {
            // Get the original filename
            $originalFilename = $file->getClientOriginalName();
    
            // Add the uploaded file to the specified media collection
            $this->team->addMedia($file->getRealPath())
                ->usingName(pathinfo($originalFilename, PATHINFO_FILENAME))
                ->usingFileName($originalFilename)
                ->toMediaCollection($collection);
    
            if ($collection === 'custom_indicators_hh') {

                $this->uploadedFileHH = $this->team->getMedia($collection)->first();
                $xlsform = $this->team->xlsforms()->first(); // TODO get the correct xlsform, for now assuming 1st
                Excel::import(new CustomIndicatorWorkbookImport($xlsform, 'household'), $this->uploadedFileHH->getPath());

            } elseif ($collection === 'custom_indicators_fw') {

                $this->uploadedFileFW = $this->team->getMedia($collection)->first();
                $xlsform = $this->team->xlsforms()->skip(1)->first(); // TODO get the correct xlsform, for now assuming 2nd
                Excel::import(new CustomIndicatorWorkbookImport($xlsform, 'fieldwork'), $this->uploadedFileFW->getPath());
                
            }
    
        } catch (Exception $e) {
            $this->addError($collection, 'An error occurred while processing the file: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.upload-custom-indicators');
    }
}
