<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\XlsformModuleVersionResource\Pages\ManageXlsformModuleVersion;
use App\Models\Xlsforms\XlsformModule;
use App\Models\Xlsforms\XlsformModuleVersion;
use Awcodes\Shout\Components\Shout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class XlsformModuleVersionResource extends Resource
{
    protected static ?string $model = XlsformModuleVersion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('xlsform_module_id')
                    ->relationship('xlsformModule', 'label')
                    ->getOptionLabelFromRecordUsing(fn(XlsformModule $xlsformModule): string => "{$xlsformModule->form->title} - $xlsformModule->name")
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Shout::make('info')
                    ->visible(fn(Forms\Get $get): bool => !$get('is_default'))
                    ->content('For modules uploaded individually, please upload the Xlsfile with the module questions. Note that in this version of the platform, every question in this module must match in "name" and "type" to an existing question in the Xlsform template. Any questions not already in the template will be ignored.'),
                Forms\Components\SpatieMediaLibraryFileUpload::make('xlsfile')
                    ->label('Upload Xlsfile with the module questions.')
                    ->collection('xlsform_file')
                    ->preserveFilenames()
                    ->downloadable()
                    ->visible(fn(Forms\Get $get): bool => !$get('is_default'))
                    ->placeholder(__('File')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('xlsformModule.form.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('xlsformModule.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
                    ->label('Default version of this module?'),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('survey_rows_count')
                    ->label('# Survey rows')
                    ->counts('surveyRows'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' =>  ManageXlsformModuleVersion::route('/'),
        ];
    }
}
