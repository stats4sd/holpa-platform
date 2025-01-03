<?php

namespace App\Livewire;

use App\Exports\XlsformTemplateLanguageExport;
use App\Imports\XlsformTemplateLanguageImport;
use App\Models\Locale;
use App\Models\Xlsforms\XlsformTemplateLanguage;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class LocaleModalTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $locale;
    public $locale_id;

    public function mount($locale_id)
    {
        $this->locale = $locale_id;
        $this->locale = Locale::findOrFail($locale_id);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->locale->xlsformTemplateLanguages()->getQuery())
            ->paginated(false)
            ->columns([
                TextColumn::make('template.title')->label('Survey'),
                TextColumn::make('status')
                    ->icon(fn(string $state): string => match ($state) {
                        'Ready for use' => 'heroicon-o-check-circle',
                        'Not added' => 'heroicon-o-exclamation-circle',
                        'Out of date' => 'heroicon-o-exclamation-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Ready for use' => 'success',
                        'Not added' => 'danger',
                        'Out of date' => 'danger',
                    })
            ])
            ->actions([
                Action::make('view_translation')
                    ->label('View translations')
                    ->visible(fn($record) => $record->status === 'Ready for use')
                    ->icon('heroicon-m-arrow-down-circle')
                    ->button()
                    ->action(function (XlsformTemplateLanguage $record) {
                        $template = $record->template;
                        $currentDate = Carbon::now()->format('Y-m-d');
                        $filename = "HOLPA - {$template->title} - translation - {$record->localeLanguageLabel} - {$currentDate}.xlsx";

                        return Excel::download(new XlsformTemplateLanguageExport($template, $record), $filename);
                    }),
                Action::make('download_translation')
                    ->label('Download translation file')
                    ->hidden(fn($record) => $record->status === 'Ready for use')
                    ->icon('heroicon-m-arrow-down-circle')
                    ->button()
                    ->action(function (XlsformTemplateLanguage $record) {
                        $template = $record->template;
                        $currentDate = Carbon::now()->format('Y-m-d');
                        $filename = "HOLPA - {$template->title} - translation - {$record->localeLanguageLabel} - {$currentDate}.xlsx";

                        return Excel::download(new XlsformTemplateLanguageExport($template, $record), $filename);
                    }),
                Action::make('upload_translation')
                    ->label('Upload translation file')
                    ->hidden(fn($record) => $record->status === 'Ready for use')
                    ->icon('heroicon-m-arrow-up-circle')
                    ->button()
                    ->modalHeading(function (XlsformTemplateLanguage $record) {
                        return 'Upload Completed Translation File for ' . $record->localeLanguageLabel;
                    })
                    ->form(function (XlsformTemplateLanguage $record) {
                        return [
                            FileUpload::make('translation_file')
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
                ]);
    }

    public function render()
    {
        return view('livewire.locale-modal-table');
    }
}
