<?php

namespace App\Filament\App\Resources;

use App\Models\GlobalIndicator;
use Filament\Forms;
use Filament\Tables;
use App\Models\Theme;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LocalIndicator;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\LocalIndicatorResource\Pages;
use App\Filament\App\Resources\LocalIndicatorResource\RelationManagers;
use Illuminate\Support\Collection;

class LocalIndicatorResource extends Resource
{
    protected static ?string $model = LocalIndicator::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('name')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('theme_id')
                    ->relationship('theme', 'name')
                    ->options(Theme::all()->pluck('displayName', 'id'))
                    ->required()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('theme.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('theme.domain')
                    ->label('Domain')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('theme.module')
                    ->label('Module')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\SelectColumn::make('global_indicator_id')
                    ->label('Global indicator')
                    ->options(fn(LocalIndicator $record): Collection => GlobalIndicator::get()->pluck('name', 'id'))
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListLocalIndicators::route('/'),
            'create' => Pages\CreateLocalIndicator::route('/create'),
            'edit' => Pages\EditLocalIndicator::route('/{record}/edit'),
        ];
    }
}
