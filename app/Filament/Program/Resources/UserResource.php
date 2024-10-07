<?php

namespace App\Filament\Program\Resources;

use Filament\Forms;
use App\Models\Team;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Program\Resources\UserResource\Pages;
use App\Filament\Program\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Programs, Teams and Users';
    protected static ?string $model = User::class;

    // only show users belong to logged in user's all accessible teams
    // user will get error 404 when access user record not belong to user
    public static function getEloquentQuery(): Builder
    {
        // find logged in user's all accessible team
        $allAccessibleTeamIds = auth()->user()->getAllAccessibleTeamIds();

        $allAccessibleTeams = Team::whereIn('id', $allAccessibleTeamIds)->get();

        // find all users of each accessible team
        $allAccessibleUserIds = array();
        foreach ($allAccessibleTeams as $team) {
            array_push($allAccessibleUserIds, $team->members->pluck('id'));
        }

        // show all users of all accessible teams only
        return parent::getEloquentQuery()->whereIn('id', $allAccessibleUserIds);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('latest_team_id')
                    ->relationship('latestTeam', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),

                // is it fine to show all teams that a user belongs to?
                // considering a user may belong to two totally not related teams, will this disclose sensitive information?
                // assume it is OK first, revise it to hide unrelated team later when necessary
                Tables\Columns\TextColumn::make('teams.name')
                    ->searchable()
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
