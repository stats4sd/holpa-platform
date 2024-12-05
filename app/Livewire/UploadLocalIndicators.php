<?php

namespace App\Livewire;

use Exception;
use App\Models\Team;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Livewire\WithFileUploads;
use App\Models\LocalIndicator;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LocalIndicatorImport;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadLocalIndicators extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use WithFileUploads;

    public Team $team;
    public Collection $localIndicators;
    public ?array $local_indicator_list = null;
    public $uploadedFile = null;

    public function mount()
    {
        $this->team = Team::find(auth()->user()->latestTeam->id);
        $this->localIndicators = $this->team->localIndicators;
        $this->form->fill();
        $this->uploadedFile = $this->team->getMedia('indicators')->first();
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

    public function uploadFile()
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
                ->toMediaCollection('indicators');

            // Update the uploaded file details
            $this->uploadedFile = $this->team->getMedia('indicators')->first();

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
            ->where('collection_name', 'indicators')
        )
        ->columns([
            TextColumn::make('file_name')
                ->label('File Name')
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Uploaded Date')
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
                    $this->uploadedFile = $this->team->getMedia('indicators')->first();

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

    public function render()
    {
        return view('livewire.upload-local-indicators');
    }
}
