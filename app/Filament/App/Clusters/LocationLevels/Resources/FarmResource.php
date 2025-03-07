<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Forms\Components\Toggle;
use App\Models\SampleFrame\Farm;
use Filament\Resources\Resource;
use App\Models\SampleFrame\LocationLevel;
use Filament\Http\Middleware\Authenticate;
use App\Filament\App\Clusters\LocationLevels;
use Stats4sd\FilamentOdkLink\Services\HelperService;
use ValentinMorice\FilamentJsonColumn\FilamentJsonColumn;
use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource\Pages;

class FarmResource extends Resource
{
    protected static ?string $model = Farm::class;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $cluster = LocationLevels::class;
    protected static ?string $tenantOwnershipRelationshipName = 'owner';

    public static function form(Form $form): Form
    {
        // find location level that has farms
        // 1. use location level name as location_id's label
        // 2. use location level's locations for user selection
        $locationLevelWithFarms = auth()->user()->latestTeam->locationLevels->where('has_farms', 1)->first();

        return $form
            ->schema([

                Forms\Components\Select::make('location_id')
                    ->label($locationLevelWithFarms->name)
                    ->options($locationLevelWithFarms->locations->pluck('name', 'id')),

                // Questions:
                // 1. farms.owner_type, farm must belong to a team. It is no longer a polymorphic-relationship. What does farms.owner_type use for?
                // 2. farms.owner_id indicates this farm belongs to which Team already, what does team_code use for?
                Forms\Components\TextInput::make('team_code')
                    ->label('Unique Code')
                    ->numeric(),

                FilamentJsonColumn::make('identifiers')
                    ->editorOnly()
                    ->editorHeight(500),

                FilamentJsonColumn::make('properties')
                    ->editorOnly()
                    ->editorHeight(500),

                Forms\Components\TextInput::make('latitude')
                    ->numeric(),

                Forms\Components\TextInput::make('longitude')
                    ->numeric(),

                Forms\Components\TextInput::make('altitude')
                    ->numeric(),

                Forms\Components\TextInput::make('accuracy')
                    ->numeric(),

                // Question:
                // 1. these two flags should not be manually modified in Create / Edit page
                // 2. should we hide them in Create / Edit page?
                Forms\Components\Checkbox::make('household_form_completed')
                    ->disabled(),

                Forms\Components\Checkbox::make('field_work_completed')
                    ->disabled(),
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
