<?php

namespace App\Filament\Admin\Resources\XlsformTemplateResource\RelationManagers;

use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Tables;
use App\Models\Language;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\XlsformTemplateLanguage;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\XlsformTemplateLanguageExport;
use App\Imports\XlsformTemplateLanguageImport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class XlsformTemplateLanguageRelationManager extends RelationManager
{
    protected static string $relationship = 'xlsformTemplateLanguages';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('language_id')
                ->label('Select the language for the new translation')
                ->options(function () {
                    return Language::all()
                        ->mapWithKeys(function ($language) {
                            return [$language->id => $language->name . ' (' . $language->iso_alpha2 . ')'];
                        })
                        ->toArray();
                })
                ->required()
                ->disabledOn('edit'),
                Forms\Components\TextInput::make('description')
                    ->placeholder('Optional description e.g., specify if this translation is for a particular dialect or region')
                    ->maxLength(255),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('language.name')
            ->heading('Languages')
            ->columns([
                Tables\Columns\TextColumn::make('language.name')
                    ->formatStateUsing(function ($state, XlsformTemplateLanguage $xlsform_template_language) {
                        return $xlsform_template_language->language->name . ' (' . $xlsform_template_language->language->iso_alpha2 . ')';
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Translation Description'),
                Tables\Columns\IconColumn::make('has_language_strings')->boolean()
                    ->label('Translations Uploaded'),
            ])
            ->actions([
                Tables\Actions\Action::make('download_translation')
                    ->label('Download translation file')
                    ->icon('heroicon-m-arrow-down-circle')
                    ->action(function () {
                        $template = $this->ownerRecord;
                        $templateTitle = $this->ownerRecord->title;
                        $currentDate = Carbon::now()->format('Y-m-d');
                        $filename = "HOLPA - {$templateTitle} - translations - {$currentDate}.xlsx";

                        return Excel::download(new XlsformTemplateLanguageExport($template), $filename);
                    }),
            
                Tables\Actions\Action::make('upload_translation')
                    ->label('Upload translation file')
                    ->icon('heroicon-m-arrow-up-circle')
                    ->modalHeading(function (XlsformTemplateLanguage $record) {
                        // Get current record details
                        $language = $record->language->name;
                        $isoAlpha2 = $record->language->iso_alpha2;
                        $description = $record->description ? ' - ' . $record->description : '';
                
                        // Build the display text
                        $languageDisplayText = $language . ' (' . $isoAlpha2 . ')' . $description;
                
                        return 'Upload Completed Translation File for ' . $languageDisplayText;
                    })                
                    ->form(function () {
                        return [
                            Forms\Components\FileUpload::make('translation_file')
                                ->label('Translation File')
                                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                                ->maxSize(10240)
                                ->required()
                                ->rules([
                                    fn(Get $get): Closure => function (string $attribute, string $value, \Closure $fail) use ($get) {

                                        // get the file from $get
                                        // (normally, we can use $value, but for FileUploads it is easier to use $get)
                                        $file = collect($get('translation_file'))->first();

                                        // Load the file as an array, getting the first sheet
                                        $rows = Excel::toArray([], $file)[0];

                                        // Get the headers from the first row and indices for name and new translation columns
                                        $headers = $rows[0];
                                        $nameIndex = array_search('name', $headers);
                                        $newTranslationIndex = array_search('new translation', $headers);

                                        // Remove the header row
                                        array_shift($rows);

                                        // Check for missing 'new_translation' when 'name' is not null
                                        $invalidRows = collect($rows)->filter(function ($row) use ($nameIndex, $newTranslationIndex) {
                                            $name = $row[$nameIndex] ?? null;
                                            $newTranslation = $row[$newTranslationIndex] ?? null;
                                            return !empty($name) && (is_null($newTranslation) || trim($newTranslation) === '');
                                        });

                                        // If there are missing translations, display an error and prevent the import
                                        if ($invalidRows->isNotEmpty()) {
                                            return $fail('The translations file cannot be uploaded as there are missing translations in the "new translation" column for the following: ' . $invalidRows->pluck($nameIndex)->implode(', '));
                                        }

                                        return true;
                                    },
                                ]),
                        ];
                    })
                    ->action(function (array $data, $livewire, XlsformTemplateLanguage $record) {
                        // Get the translation file
                        $uploadedFile = $data['translation_file'];
                        $file = Storage::path($uploadedFile);

                        // Proceed with the import
                        Excel::import(new XlsformTemplateLanguageImport($record), $file);

                        // Display success message
                        Notification::make()
                            ->title('Success')
                            ->body('Translations uploaded successfully for ' . $record->language->name . ' (' . $record->language->iso_alpha2 . ')' . ($record->description ? ' - ' . $record->description : ''))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()
                    ->modalHeading(function (XlsformTemplateLanguage $record) {
                        return 'Edit xlsform template language ' . $record->language->name;
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->disableCreateAnother()
                    ->label('Add new language')
            ]);
    }
}
