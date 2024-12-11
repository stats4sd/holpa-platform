<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TeamResource\Pages;
use App\Filament\Admin\Resources\TeamResource\RelationManagers\LocalesRelationManager;
use App\Filament\Admin\Resources\TeamResource\RelationManagers\XlsformsRelationManager;
use App\Models\Team;
use Filament\Tables;
use Filament\Tables\Table;
use Stats4sd\FilamentTeamManagement\Filament\Admin\Resources\TeamResource\RelationManagers\UsersRelationManager;
use Stats4sd\FilamentTeamManagement\Filament\Admin\Resources\TeamResource\RelationManagers\InvitesRelationManager;

class TeamResource extends \Stats4sd\FilamentTeamManagement\Filament\Admin\Resources\TeamResource
{
    protected static ?string $model = Team::class;

    protected static bool $shouldRegisterNavigation = true;

    // if this class is called TeamResource.php, table() function cannot override table() function in superclass.
    // we need to rename this class as NewTeamResource.php, then table() function can be overwritten.
    // same route name maybe the possible cause.
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('programs.name')
                    ->searchable()
                    ->badge()
                    ->color('success')
                    ->visible(config('filament-team-management.use_programs')),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('# Users')
                    ->counts('users')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invites_count')
                    ->label('# Invites')
                    ->counts('invites')
                    ->sortable(),
                Tables\Columns\TextColumn::make('xlsforms_count')
                    ->label('# Xlsforms')
                    ->counts('xlsforms')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
            'view' => Pages\ViewTeam::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
            InvitesRelationManager::class,
            XlsformsRelationManager::class,
            LocalesRelationManager::class,
        ];
    }
}
