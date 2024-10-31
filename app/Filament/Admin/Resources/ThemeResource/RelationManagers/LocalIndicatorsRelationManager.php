<?php

namespace App\Filament\Admin\Resources\ThemeResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LocalIndicatorsRelationManager extends RelationManager
{
    protected static string $relationship = 'localIndicators';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('theme.name')
                    ->label('Theme')
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
                Tables\Columns\TextColumn::make('globalindicator_count')
                    ->label('# Global indicators')
                    ->counts('globalindicator')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AssociateAction::make()
                    ->associateAnother(false),
            ])
            ->actions([
                Tables\Actions\DissociateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                ]),
            ]);
    }
}
