<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Theme;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\GlobalIndicator;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\GlobalIndicatorResource\Pages;
use App\Filament\Admin\Resources\GlobalIndicatorResource\RelationManagers;

class GlobalIndicatorResource extends Resource
{
    protected static ?string $model = GlobalIndicator::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?string $navigationGroup = 'HOLPA Indicators';

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
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('localindicators_count')
                    ->label('# Local indicators')
                    ->counts('localindicators')
                    ->sortable(),
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
            RelationManagers\LocalIndicatorsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGlobalIndicators::route('/'),
            'create' => Pages\CreateGlobalIndicator::route('/create'),
            'edit' => Pages\EditGlobalIndicator::route('/{record}/edit'),
        ];
    }
}
