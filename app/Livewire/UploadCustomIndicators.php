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
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadCustomIndicators extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Team $team;
    public ?array $custom_indicators = null;
    public $uploadedFile = null;

    public function mount()
    {
        $this->team = Team::find(auth()->user()->latestTeam->id);
        $this->form->fill();
        $this->uploadedFile = $this->team->getMedia('custom_indicators')->first();
    }

    public function table(Table $table): Table
    {
        return $table
        ->query(Media::query()
            ->where('model_id', $this->team->id)
            ->where('model_type', Team::class)
            ->where('collection_name', 'custom_indicators')
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

                    // TODO: Delete imported custom indicator data

                    // Update the uploaded file details
                    $this->uploadedFile = $this->team->getMedia('custom_indicators')->first();

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
                FileUpload::make('custom_indicators')
                    ->label('')
                    ->required()
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                    ->maxSize(10240)
                    ->preserveFilenames()
            ]);
    }

    public function uploadFile()
    {
        // Check that a file is uploaded
        if (empty($this->custom_indicators) || !is_array($this->custom_indicators)) {
            $this->addError('custom_indicators', 'Please upload a file before proceeding.');
            return;
        }

        // Get the file from the array
        $file = reset($this->custom_indicators);

        try {
            // Get the original filename
            $originalFilename = $file->getClientOriginalName();

            // Add the uploaded file to the media collection
            $this->team->addMedia($file->getRealPath())
                ->usingName(pathinfo($originalFilename, PATHINFO_FILENAME))
                ->usingFileName($originalFilename)
                ->toMediaCollection('custom_indicators');

            // Update the uploaded file details
            $this->uploadedFile = $this->team->getMedia('custom_indicators')->first();

            // Proceed with the import
            Excel::import(new CustomIndicatorImport($this->team), $this->uploadedFile->getPath());

            // Display success message
            Notification::make()
                ->title('Success')
                ->body('File uploaded successfully!')
                ->success()
                ->send();

        } catch (Exception $e) {
            $this->addError('custom_indicators', 'An error occurred while uploading the file.');
        }
    }

    public function render()
    {
        return view('livewire.upload-custom-indicators');
    }
}
