<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\XlsformTemplateModuleTypeResource\Pages;
use App\Filament\Admin\Resources\XlsformTemplateModuleTypeResource\RelationManagers;
use App\Models\XlsformModule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class XlsformModuleResource extends Resource
{
    protected static ?string $model = XlsformModule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Enter the readable name of the module type')
                    ->hint('e.g. "Dietary Diversity"')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('group_name')
                    ->label('Enter the name of the group as it appears in the ODK form')
                    ->hint('e.g. if you have "begin_group" with a name of "diet_diversity", enter "diet_diversity"')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageXlsformModule::route('/'),
        ];
    }
}
