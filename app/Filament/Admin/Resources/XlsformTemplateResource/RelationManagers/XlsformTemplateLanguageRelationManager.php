<?php

namespace App\Filament\Admin\Resources\XlsformTemplateResource\RelationManagers;

use Closure;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Locale;
use Filament\Forms\Get;
use App\Models\Language;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Group;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\XlsformTemplateLanguage;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Exports\XlsformTemplateLanguageExport;
use App\Imports\XlsformTemplateLanguageImport;
use Illuminate\Validation\ValidationException;
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
                    ->required()
                    ->disabledOn('edit')
                    ->options(function () {
                        return Language::all()
                            ->mapWithKeys(function ($language) {
                                return [$language->id => $language->name . ' (' . $language->iso_alpha2 . ')'];
                            })
                            ->toArray();
                    }),
                Group::make()
                    ->relationship('locale')
                    ->schema([
                        Forms\Components\TextInput::make('description')

                            ->placeholder('Optional description e.g., specify if this translation is for a particular dialect or region')
                            ->maxLength(255)
                    ])
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
                Tables\Columns\TextColumn::make('locale.description')
                    ->label('Translation Description'),
                Tables\Columns\IconColumn::make('has_language_strings')->boolean()
                    ->label('Translations Uploaded')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('needs_update')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->needs_update ? 'Update Required' : null),
            ])
            ->actions([
                Tables\Actions\Action::make('download_translation')
                    ->label('Download translation file')
                    ->icon('heroicon-m-arrow-down-circle')
                    ->action(function (XlsformTemplateLanguage $record) {
                        $template = $this->ownerRecord;
                        $templateTitle = $this->ownerRecord->title;
                        $currentDate = Carbon::now()->format('Y-m-d');
                        $filename = "HOLPA - {$templateTitle} - translation - {$record->localeLanguageLabel} - {$currentDate}.xlsx";

                        return Excel::download(new XlsformTemplateLanguageExport($template, $record), $filename);
                    }),
            
                Tables\Actions\Action::make('upload_translation')
                    ->label('Upload translation file')
                    ->icon('heroicon-m-arrow-up-circle')
                    ->modalHeading(function (XlsformTemplateLanguage $record) {                
                        return 'Upload Completed Translation File for ' . $record->localeLanguageLabel;
                    })                
                    ->form(function (XlsformTemplateLanguage $record) {
                        return [
                            Forms\Components\FileUpload::make('translation_file')
                                ->label('Translation File')
                                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                                ->maxSize(10240)
                                ->required()
                                ->rules([
                                    // Check that the translation column exists and that there are no missing translation strings
                                    fn(Get $get): Closure => function (string $attribute, string $value, \Closure $fail) use ($get, $record) {
                                        // get the language label
                                        $languageLabel = $record->localeLanguageLabel;

                                        // get the file from $get
                                        $file = collect($get('translation_file'))->first();
                                
                                        // Load the file as an array, getting the first sheet
                                        $rows = Excel::toArray([], $file)[0];
                                
                                        // Get the headers from the first row
                                        $headers = $rows[0];
                                
                                        // Get the indices for 'name' and the languageLabel column
                                        $nameIndex = array_search('name', $headers);
                                        $currentTranslationIndex = array_search($languageLabel, $headers);
                                
                                        // If the languageLabel column is missing in the file, validation fails
                                        if ($currentTranslationIndex === false) {
                                            return $fail('The translations file must contain the translation column"' . $languageLabel . '"');
                                        }
                                
                                        // Remove the header row
                                        array_shift($rows);
                                
                                        // Check for missing translations in the 'languageLabel' column (when 'name' is not null to avoid empty rows)
                                        $invalidRows = collect($rows)->filter(function ($row) use ($nameIndex, $currentTranslationIndex) {
                                            $name = $row[$nameIndex] ?? null;
                                            $currentTranslation = $row[$currentTranslationIndex] ?? null;
                                            return !empty($name) && (is_null($currentTranslation) || trim($currentTranslation) === '');
                                        });
                                
                                        // If there are missing translations, display an error and prevent the import
                                        if ($invalidRows->isNotEmpty()) {
                                            // Map the invalid rows to display 'name (translation type)'
                                            $invalidNamesWithType = $invalidRows->flatMap(function ($row) use ($nameIndex, $headers, $currentTranslationIndex) {
                                                $name = $row[$nameIndex];
                                                $translationType = $row[array_search('translation type', $headers)];
                                        
                                                return (is_null($row[$currentTranslationIndex]) || trim($row[$currentTranslationIndex]) === '') 
                                                    ? [$name . ' (' . $translationType . ')'] 
                                                    : []; // Return an empty array if the translation is present
                                            });
                                        
                                            return $fail('The translations file cannot be uploaded as there are missing translations in the "' . $languageLabel . '" column for the following: ' . $invalidNamesWithType->implode(', '));
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
                            ->body('Translations uploaded successfully for ' . $record->localeLanguageLabel)
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()
                    ->modalHeading(function (XlsformTemplateLanguage $record) {
                        return 'Edit xlsform template language ' . $record->localeLanguageLabel;
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->disableCreateAnother()
                    ->label('Add new language')
                    ->mutateFormDataUsing(function (array $data): array {
                        $locale = Locale::create([
                            'language_id' => $data['language_id'],
                            'description' => $data['description'] ?? null,
                        ]);

                        $data['locale_id'] = $locale->id;

                        return $data;
                    })
            ]);
    }
}
