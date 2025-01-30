<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\XlsformResource\Pages;
use App\Filament\App\Resources\XlsformResource\RelationManagers;
use Filament\Forms\Form;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Xlsforms\Xlsform;

class XlsformResource extends Resource
{
    protected static ?string $model = Xlsform::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'owner';

    protected static ?string $navigationGroup = 'Settings';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ViewEntry::make('submission_summary')
                    ->view('submission_summary_wrapper')
                    ->columnSpanFull()
            ]);
    }

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
                TextColumn::make('title')
                    ->grow(false),
                TextColumn::make('status')
                    ->color(fn($state) => match ($state) {
                        'UPDATES AVAILABLE' => 'danger',
                        'LIVE' => 'success',
                        'DRAFT' => 'info',
                        default => 'light',
                    })
                    ->iconColor(fn($state) => match ($state) {
                        'UPDATES AVAILABLE' => 'danger',
                        'LIVE' => 'success',
                        'DRAFT' => 'info',
                        default => 'light',
                    })
                    ->icon(fn($state) => match ($state) {
                        'UPDATES AVAILABLE' => 'heroicon-o-exclamation-circle',
                        'LIVE' => 'heroicon-o-check',
                        'DRAFT' => 'heroicon-o-pencil',
                        default => 'heroicon-o-information-circle',
                    })
                    ->label('Status'),

                TextColumn::make('live_submissions_count')
                    ->label('No. of Submissions'),
                TextColumn::make('submissions_count')
                    ->label('Submissions in database')
                    ->counts('submissions'),
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
            RelationManagers\SubmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListXlsforms::route('/'),
            'view' => Pages\ViewXlsform::route('/{record}'),
        ];
    }
}
