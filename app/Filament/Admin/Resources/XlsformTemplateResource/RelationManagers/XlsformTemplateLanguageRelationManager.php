<?php

namespace App\Filament\Admin\Resources\XlsformTemplateResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\XlsformTemplateLanguage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class XlsformTemplateLanguageRelationManager extends RelationManager
{
    protected static string $relationship = 'xlsformTemplateLanguages';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->heading('Languages')
            ->columns([
                Tables\Columns\TextColumn::make('language.name')
                    ->formatStateUsing(function ($state, XlsformTemplateLanguage $xlsform_template_language) {
                        return $xlsform_template_language->language->name . ' (' . $xlsform_template_language->language->iso_alpha2 . ')';
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Translation Description'),
            ])
            ->actions([
                Tables\Actions\Action::make('download_translation')
                ->icon('heroicon-o-arrow-down-circle')
            ]
            )
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('download_translation')
                    ->label('Download file to add translations')
                    ->icon('heroicon-o-arrow-down-circle'),
                Tables\Actions\CreateAction::make()
                    ->label('Upload translation file')
                    ->icon('heroicon-o-arrow-up-circle'),
            ]);
    }
}
