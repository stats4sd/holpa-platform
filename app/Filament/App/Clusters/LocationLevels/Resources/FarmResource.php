<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources;

use App\Filament\App\Clusters\LocationLevels;
use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource\Pages;
use App\Models\SampleFrame\Farm;
use App\Models\SampleFrame\LocationLevel;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Stats4sd\FilamentOdkLink\Services\HelperService;

class FarmResource extends Resource
{
    protected static ?string $model = Farm::class;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $cluster = LocationLevels::class;
    protected static ?string $tenantOwnershipRelationshipName = 'owner';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {

        $farms = Farm::all()->where('owner_id', HelperService::getCurrentOwner()->id);

        $locationLevelColumns = $farms->map(fn(Farm $farm) => $farm->location->locationLevel)
            ->unique()
            ->values()
            ->map(
                fn(LocationLevel $locationLevel) => Tables\Columns\TextColumn::make("location_{$locationLevel->id}")
                    ->getStateUsing(fn($record) => $record->location->location_level_id === $locationLevel->id ? $record->location->name : '')
                    ->label($locationLevel->name)
                    ->sortable()
                    ->searchable()
            );


        $identifiers = $farms->map(fn(Farm $farm) => $farm->identifiers?->keys())
            ->flatten()->unique()->values();

        $idColumns = $identifiers->map(fn($identifier) => Tables\Columns\TextColumn::make("identifiers.{$identifier}")->label(ucfirst($identifier))->sortable()->searchable());

        $properties = $farms->map(fn(Farm $farm) => $farm->properties?->keys())
            ->flatten()->unique()->values();

        $propertyColumns = $properties->map(fn($property) => Tables\Columns\TextColumn::make("properties.{$property}")->label(ucfirst($property))->sortable()->searchable());

        return $table
            ->columns([
                ...$locationLevelColumns,
                Tables\Columns\TextColumn::make('team_code')->label('Unique Code')
                    ->sortable()
                    ->searchable(),
                ...$idColumns,
                ...$propertyColumns,
            ])
            ->filters([])
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
            'index' => Pages\ListFarms::route('/'),
            'create' => Pages\CreateFarm::route('/create'),
            'edit' => Pages\EditFarm::route('/{record}/edit'),
        ];
    }
}
