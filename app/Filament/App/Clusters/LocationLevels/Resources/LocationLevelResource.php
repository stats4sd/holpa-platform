<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources;

use App\Filament\App\Clusters\LocationLevels;
use App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource\Pages;
use App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource\RelationManagers\LocationsRelationManager;
use App\Models\SampleFrame\LocationLevel;
use App\Services\HelperService;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LocationLevelResource extends Resource
{
    protected static ?string $model = LocationLevel::class;

    protected static ?string $tenantOwnershipRelationshipName = 'owner';

    protected static ?string $cluster = LocationLevels::class;

    public static function getNavigationItems(): array
    {
        // make sure the original nav item is only 'active' when the index page is active.
        $original = collect(parent::getNavigationItems())
            ->map(function ($item) {
                return $item->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName().'.index'));
            })->toArray();

        $baseRoute = static::getUrl('index');

        $navItems = LocationLevel::query()
            ->orderBy('parent_id')
            ->get()
            ->map(function ($level) use ($baseRoute) {
                return NavigationItem::make(Str::plural($level->name))
                    ->url($baseRoute.'/'.$level->slug)
                    ->isActiveWhen(function () use ($level) {
                        $isViewRoute = request()->routeIs(static::getRouteBaseName().'.view');
                        $isMatchingRecord = request()->route('record') === $level->slug;

                        return $isViewRoute && $isMatchingRecord;
                    });
            });

        $farmNavItem = NavigationItem::make('Farms')
            ->url(FarmResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs(FarmResource::getRouteBaseName().'.index'));

        return array_merge($original, $navItems->toArray(), [$farmNavItem]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('parent_id')
                    ->label('Is this location level a sub-level of another level?')
                    ->helperText('E.g. "Village" may be a sub-level of "District", and "District" may be a sub-level of "Province".')
                    ->relationship('parent', 'name')
                    ->hidden(fn (?LocationLevel $record) => $record && $record->top_level === 1),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Toggle::make('has_farms')
                    ->label('Are there farms at this level?')
                    ->helperText('Only say yes if there are farms directly at this location level, not in a lower location level. E.g. "Village" may have farms, but "District" may not.'),
                Hidden::make('owner_id')
                    ->default(fn () => HelperService::getCurrentOwner()->id),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->sortable()
                    ->placeholder('Top Level'),
                Tables\Columns\TextColumn::make('locations_count')
                    ->counts('locations')
                    ->label('No. of Entries')
                    ->sortable(),
                Tables\Columns\IconColumn::make('has_farms')
                    ->boolean()
                    ->sortable(),
            ])
            ->paginated(false)
            ->defaultSort('parent_id', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Key Details')
                ->schema([
                    TextEntry::make('name')->label('Level'),
                    TextEntry::make('parent.name')->label('Parent Level')->hidden(fn (LocationLevel $record) => $record->top_level === 1),
                ]),
        ])
            ->columns(2);
    }

    public static function getRelations(): array
    {
        return [
            LocationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocationLevels::route('/'),
            'view' => Pages\ViewLocationLevel::route('/{record}'),
        ];
    }
}
