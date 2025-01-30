<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SubmissionResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

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

                Tables\Columns\TextColumn::make('farm_name')
                    ->getStateUsing(function ($record) {
                        return $record->content['context']['farm_location']['farm_name'];
                    }),
                Tables\Columns\TextColumn::make('consent')
                    ->getStateUsing(function ($record) {
                        if (isset($record->content['consent'])) {
                            return $record->content['consent']['consent_interview'] === "1" ? "Yes" : "No";
                        } else {
                            // for fieldwork;
                            return $record->content['consent_begin_group']['consent_1'] === "1" ? "Yes" : "No";
                        }

                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('edit')->label('View/Edit in Enketo')
                    ->action(function (Submission $record) {
                        $odkProjectId = $record->xlsformVersion->xlsform->owner->odkProject->id;
                        $odkXlsformId = $record->xlsformVersion->xlsform->odk_id;

                        $url = config('filament-odk-link.odk.base_endpoint') . "/projects/$odkProjectId/forms/$odkXlsformId/submissions/$record->odk_id/edit";

                        $token = app()->make(OdkLinkService::class)->authenticate();

                        $redirect = Http::withToken($token)
                            ->get($url)
                        ->transferStats->getHandlerStat('url');

                        return redirect($redirect);

                    }),
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
