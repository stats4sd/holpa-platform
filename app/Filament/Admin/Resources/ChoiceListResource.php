<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChoiceListResource\Pages;
use App\Filament\Admin\Resources\ChoiceListResource\RelationManagers;
use App\Models\ChoiceList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChoiceListResource extends Resource
{
    protected static ?string $model = ChoiceList::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('xlsformTemplate.title'),
                Tables\Columns\TextColumn::make('list_name'),
                Tables\Columns\IconColumn::make('is_localisable')->boolean(),
                Tables\Columns\IconColumn::make('is_dataset')->boolean(),

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
            'index' => Pages\ListChoiceLists::route('/'),
            'create' => Pages\CreateChoiceList::route('/create'),
            'edit' => Pages\EditChoiceList::route('/{record}/edit'),
        ];
    }
}
