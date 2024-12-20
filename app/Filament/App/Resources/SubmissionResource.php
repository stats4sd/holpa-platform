<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SubmissionResource\Pages;
use App\Filament\App\Resources\SubmissionResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static bool $isScopedToTenant = false;

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
            ->heading('')
            ->emptyStateDescription('Submissions will appear here once they have been submitted through ODK Collect and synced with the server.')
            ->recordTitleAttribute('odk_id')
            ->columns([
                Tables\Columns\TextColumn::make('odk_id'),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->sortable(),

                // comment below code temporary, customisation is required for HOLPA

                //  Tables\Columns\TextColumn::make('enumerator')
                //  ->getStateUsing(function ($record) {
                //      $enumeratorId = $record->content['survey_start']['inquirer_choice'];
                //      if($enumeratorId === "77") {
                //          return $record->content['survey_start']['inquirer_text'];
                //      }
                //      return Enumerator::firstWhere('name', $record->content['survey_start']['inquirer_choice'])->label ?? '~not found~';
                //  }),
                //  Tables\Columns\TextColumn::make('farm_name')
                //      ->getStateUsing(function ($record) {
                //          return $record->content['reg']['farm_name'];
                //      }),
                //  Tables\Columns\TextColumn::make('respondent_available')
                //      ->getStateUsing(function ($record) {
                //          return $record->content['reg']['respondent_check']['respondent_available'];
                //      }),
                //  Tables\Columns\TextColumn::make('consent')
                //      ->getStateUsing(function ($record) {
                //          return $record->content['consent_grp']['consent'] === "1" ? "Yes" : "No";
                //      }),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSubmissions::route('/'),
        ];
    }
}
