<?php

namespace App\Filament\Admin\Resources\XlsformTemplateResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Language;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\XlsformTemplateLanguage;
use Illuminate\Support\Facades\Storage;
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
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalHeading(function (XlsformTemplateLanguage $record) {
                    return 'Edit xlsform template language ' . $record->language->name;
                }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('download_translation')
                    ->label('Download translation file')
                    ->icon('heroicon-o-arrow-down-circle')
                    ->action(function () {
                        $template = $this->ownerRecord;
                        $templateTitle = $this->ownerRecord->title;
                        $currentDate = Carbon::now()->format('Y-m-d');
                        $filename = "HOLPA - {$templateTitle} - translations - {$currentDate}.xlsx";

                        return Excel::download(new XlsformTemplateLanguageExport($template), $filename);
                    }),
                Tables\Actions\CreateAction::make()
                    ->label('Upload completed translation file')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->modalHeading('Upload Completed Translation File')
                    ->disableCreateAnother()
                    ->form(function (Tables\Actions\CreateAction $action) {
                        return [
                            Forms\Components\FileUpload::make('translation_file')
                                ->label('Translation File')
                                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                                ->maxSize(10240)
                                ->required(),
                            Forms\Components\Select::make('language_id')
                                ->label('Select the language for the new translation')
                                ->options(function () {
                                    return Language::all()
                                        ->mapWithKeys(function ($language) {
                                            return [$language->id => $language->name . ' (' . $language->iso_alpha2 . ')'];
                                        })
                                        ->toArray();
                                })
                                ->required(),
                            Forms\Components\TextInput::make('description')
                                ->label('Description')
                                ->maxLength(255)
                                ->placeholder('Optional description e.g., specify if this translation is for a particular dialect or region'),
                        ];
                    })
                    ->action(function (array $data, $livewire) {
                        // Get the translation file
                        $uploadedFile = $data['translation_file'];
                        $file = Storage::path($uploadedFile);
                        
                        // Create new template language
                        $templateLanguage = XlsformTemplateLanguage::create([
                            'language_id' => $data['language_id'],
                            'xlsform_template_id' => $livewire->ownerRecord->id,
                            'description' => $data['description'] ?? null,
                        ]);

                        Excel::import(new XlsformTemplateLanguageImport($templateLanguage), $file);
                    })
            ]);
    }
}
