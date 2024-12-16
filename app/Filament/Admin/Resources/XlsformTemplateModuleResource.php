<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\XlsformTemplateModuleResource\Pages;
use App\Filament\Admin\Resources\XlsformTemplateModuleResource\RelationManagers;
use App\Models\XlsformTemplateModule;
use Awcodes\Shout\Components\Shout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithXlsFormDrafts;

class XlsformTemplateModuleResource extends Resource
{
    protected static ?string $model = XlsformTemplateModule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('xlsform_template_id')
                    ->relationship('xlsformTemplate', 'title')
                    ->required(),
                Forms\Components\Select::make('xlsform_template_module_type_id')
                    ->relationship('xlsformTemplateModuleType', 'label')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Shout::make('info')
                    ->content('Please upload the Xlsfile with the module questions. Note that in this version of the platform, every question in this module must match in "name" and "type" to an existing question in the Xlsform template. Any questions not already in the template will be ignored.'),
                Forms\Components\SpatieMediaLibraryFileUpload::make('xlsfile')
                    ->label('Upload Xlsfile with the module questions.')
                    ->collection('xlsform_file')
                    ->preserveFilenames()
                    ->downloadable()
                    ->required()
                    ->placeholder(__('File')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('xlsformTemplate.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('xlsformTemplateModuleType.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('surveyRows_count')
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
            'index' => Pages\ManageXlsformTemplateModules::route('/'),
        ];
    }
}
