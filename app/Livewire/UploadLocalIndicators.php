<?php

namespace App\Livewire;

use App\Imports\LocalIndicatorImport;
use App\Models\Holpa\LocalIndicator;
use App\Models\Team;
use App\Services\HelperService;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Exception;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadLocalIndicators extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use WithFileUploads;

    public Team $team;

    public ?Media $uploadedFile = null;
    public ?array $data;

    public function mount(): void
    {
        $this->team = HelperService::getCurrentOwner();

        $this->form->fill($this->team->toArray());
        $this->uploadedFile = $this->team->getMedia('local_indicators')->first();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->model($this->team)
            ->schema([
                Fieldset::make('Upload Local Indicators')
                    ->columns(6)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('local_indicator_list')
                            ->columnSpan(5)
                            ->collection('local_indicators')
                            ->downloadable()
                            ->label('')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                            ->maxSize(10240)
                            ->preserveFilenames()
                            ->helperText(fn(self $livewire) => new HtmlString('<span class="text-red-700">' . collect($livewire->getErrorBag()->get('local_indicator_list'))->join('<br/>') . '</span>')),

                        Actions::make([
                            Actions\Action::make('save_file')
                            ->extraAttributes(['class' => ' buttona'])

                                ->label('Save File')
                                ->action(fn(Get $get) => $this->uploadFile($get('local_indicator_list'))),
                        ]),
                    ]),
                Fieldset::make('Local Indicators List')
                    ->columns(1)
                    ->schema([
                        TableRepeater::make('localIndicators')
                            ->label('')
                            ->relationship('localIndicators')
                            ->headers([
                                Header::make('name')
                                    ->width('70%'),
                                Header::make('domain'),
                            ])
                            ->schema([
                                TextInput::make('name')->required(),
                                Select::make('domain_id')
                                    ->relationship('domain', 'name')->required(),
                            ])
                        ->emptyLabel('No local indicators added - click "Add Local Indicator" below to create a new entry')
                        ->addActionLabel('Add Local Indicator')
                        
                    ]),
            ]);
    }

    public function saveIndicators(): void
    {
        $this->form->getState();
        $this->form->saveRelationships();
    }

    public function uploadFile($localIndicatorList): void
    {
        // Check that a file is uploaded
        if (empty($localIndicatorList) || !is_array($localIndicatorList)) {
            $this->addError('local_indicator_list', 'Please upload a file before proceeding.');
            return;
        }

        // Get the file from the array
        $file = reset($localIndicatorList);

        try {
            // Proceed with the import
            Excel::import(new LocalIndicatorImport($this->team), $file->getRealPath());

            $this->team->refresh();
            $this->form->fill($this->team->toArray());

            // Display success message
            Notification::make()
                ->title('Success')
                ->body('File uploaded successfully!')
                ->success()
                ->send();

        } catch (ValidationException $e) {

            ray($e->validator->errors());

            $errors = collect($e->validator->errors())
                ->map(function ($errors, $key) {
                    $keyRow = explode('.', $key)[0];
                    $keyColumn = explode('.', $key)[1];
                    $errors = collect($errors)->join('; ');

                    return "Row $keyRow, Column $keyColumn: $errors";
                });

            ray(new HtmlString('It looks like the file you uploaded is not valid. Please check the file and try again. Errors Found: <br/><br/>' . $errors->join('<br/>')));

            $this->addError('local_indicator_list', 'It looks like the file you uploaded is not valid. Please check the file and try again. Errors Found: <br/><br/>' . $errors->join('<br/>'));
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
