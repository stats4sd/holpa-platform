<?php

// add a filament custom page in HOLPA main repo temporary, will move it to submodule filament-odk-link later
namespace App\Filament\Admin\Pages;

use Exception;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Holpa\LocalIndicator;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class CustomModules extends Page implements HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Module Builder';

    protected static string $view = 'filament.admin.pages.custom-modules';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LocalIndicator::query()
                    ->where('team_id', auth()->user()->latestTeam->id),
            )
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Hidden::make('domain_id')
                            ->default(1)
                            ->required(),
                    ]),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(LocalIndicator::class)
                    ->form([
                        Hidden::make('team_id')
                            ->required()
                            ->default(auth()->user()->latestTeam->id),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Hidden::make('domain_id')
                            ->default(1)
                            ->required(),
                    ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}
