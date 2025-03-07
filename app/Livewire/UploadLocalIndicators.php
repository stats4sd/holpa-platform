<?php

namespace App\Livewire;

use App\Imports\LocalIndicatorImport;
use App\Models\Team;
use App\Services\HelperService;
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
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadLocalIndicators extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use WithFileUploads;

    public Team $team;
    public Collection $localIndicators;
    public ?array $local_indicator_list = null;
    public ?Media $uploadedFile = null;

    public function mount(): void
    {
        $this->team = HelperService::getCurrentOwner();
        $this->localIndicators = $this->team->localIndicators;
        $this->form->fill();
        $this->uploadedFile = $this->team->getMedia('local_indicators')->first();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('local_indicator_list')
                    ->label('')
                    ->required()
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                    ->maxSize(10240)
                    ->preserveFilenames()
            ]);
    }

    public function uploadFile(): void
    {
        // Check that a file is uploaded
        if (empty($this->local_indicator_list) || !is_array($this->local_indicator_list)) {
            $this->addError('local_indicator_list', 'Please upload a file before proceeding.');
            return;
        }

        // Get the file from the array
        $file = reset($this->local_indicator_list);

        try {
            // Get the original filename
            $originalFilename = $file->getClientOriginalName();

            // Add the uploaded file to the media collection
            $this->team->addMedia($file->getRealPath())
                ->usingName(pathinfo($originalFilename, PATHINFO_FILENAME))
                ->usingFileName($originalFilename)
                ->toMediaCollection('local_indicators');

            // Update the uploaded file details
            $this->uploadedFile = $this->team->getMedia('local_indicators')->first();

            // Proceed with the import
            Excel::import(new LocalIndicatorImport($this->team), $this->uploadedFile->getPath());

            // Display success message
            Notification::make()
                ->title('Success')
                ->body('File uploaded successfully!')
                ->success()
                ->send();

        } catch (Exception $e) {
            $this->addError('local_indicator_list', 'An error occurred while uploading the file.');
        }
    }

    public function table(Table $table): Table
    {
        return $table
        ->query(Media::query()
            ->where('model_id', $this->team->id)
            ->where('model_type', Team::class)
            ->where('collection_name', 'local_indicators')
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

                    // Delete the local indicator records
                    $this->team->localIndicators()->delete();

                    // Update the uploaded file details
                    $this->uploadedFile = $this->team->getMedia('local_indicators')->first();

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

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View|null
    {
        return view('livewire.upload-local-indicators');
    }
}
